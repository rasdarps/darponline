<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\SaleReceiptEvent;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class SaleReceiptWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private function createUserWithPermissions(array $permissionNames = []): User
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $user = User::factory()->create([
            'name' => 'Test User',
            'username' => 'test.user',
            'email' => 'test.user@example.com',
            'phone' => '0240000000',
        ]);

        foreach ($permissionNames as $permissionName) {
            $permission = Permission::findOrCreate($permissionName, 'web');
            $user->givePermissionTo($permission);
        }

        return $user;
    }

    private function createSale(array $overrides = []): Sale
    {
        $category = Category::forceCreate([
            'name' => 'Test Category',
        ]);

        $unit = Unit::forceCreate([
            'name' => 'Each',
        ]);

        $customer = Customer::forceCreate([
            'name' => 'Walk-in Customer',
            'mobile_no' => '0240000000',
            'email' => 'walkin@example.com',
            'address' => 'Test Address',
        ]);

        $product = Product::forceCreate([
            'name' => 'Test Product',
            'category_id' => $category->id,
            'unit_id' => $unit->id,
            'quantity' => 10,
            'stock_level' => 1,
            'barcode' => '2000000000013',
            'cost_price' => 5,
            'selling_price' => 10,
        ]);

        $sale = Sale::forceCreate(array_merge([
            'sale_no' => '001',
            'date' => now()->toDateString(),
            'description' => 'Test sale',
            'delivery_code' => 'DLV-00000001-ABC123',
            'created_by' => $overrides['created_by'] ?? null,
        ], $overrides));

        SaleDetail::create([
            'date' => now()->toDateString(),
            'sale_id' => $sale->id,
            'category_id' => $category->id,
            'product_id' => $product->id,
            'selling_qty' => 1,
            'unit_selling_price' => 10,
            'selling_price' => 10,
            'unit_id' => $unit->id,
        ]);

        Payment::forceCreate([
            'sale_id' => $sale->id,
            'customer_id' => $customer->id,
            'discount_amount' => 0,
            'total_amount' => 10,
            'taxable_amount' => 8.33,
            'nhil_amount' => 0.21,
            'getfund_amount' => 0.21,
            'vat_amount' => 1.25,
            'cash_tendered' => 10,
            'change_due' => 0,
            'paid_amount' => 10,
        ]);

        return $sale;
    }

    public function test_original_print_requires_permission(): void
    {
        $user = $this->createUserWithPermissions([]);
        $sale = $this->createSale(['created_by' => $user->id]);

        $this->actingAs($user)
            ->get(route('print.sale', Crypt::encrypt($sale->id)))
            ->assertForbidden();
    }

    public function test_original_print_stamps_sale_and_creates_audit_event(): void
    {
        $user = $this->createUserWithPermissions(['sale-print']);
        $sale = $this->createSale(['created_by' => $user->id]);

        $response = $this->actingAs($user)
            ->get(route('print.sale', Crypt::encrypt($sale->id)));

        $response->assertOk();

        $sale->refresh();

        $this->assertNotNull($sale->printed_at);
        $this->assertSame($user->id, $sale->printed_by);
        $this->assertDatabaseHas('sale_receipt_events', [
            'sale_id' => $sale->id,
            'event_type' => 'original_print',
            'operator_id' => $user->id,
        ]);
        $this->assertSame(1, SaleReceiptEvent::where('sale_id', $sale->id)->count());
    }

    public function test_second_original_print_request_is_rejected(): void
    {
        $user = $this->createUserWithPermissions(['sale-print']);
        $sale = $this->createSale(['created_by' => $user->id]);

        $this->actingAs($user)
            ->get(route('print.sale', Crypt::encrypt($sale->id)))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('print.sale', Crypt::encrypt($sale->id)))
            ->assertRedirect(route('sales.index'))
            ->assertSessionHas('error');

        $this->assertSame(1, SaleReceiptEvent::where('sale_id', $sale->id)->count());
    }

    public function test_reprint_requires_reason_and_rejects_cancelled_sales(): void
    {
        $user = $this->createUserWithPermissions(['sale-reprint']);
        $sale = $this->createSale(['created_by' => $user->id, 'printed_at' => now(), 'printed_by' => $user->id]);

        $this->actingAs($user)
            ->post(route('sales.reprint', Crypt::encrypt($sale->id)), [])
            ->assertSessionHasErrors('reason');

        $sale->forceFill([
            'cancelled_at' => now(),
            'cancelled_by' => $user->id,
            'cancellation_reason' => 'Cancelled for test',
        ])->save();

        $this->actingAs($user)
            ->post(route('sales.reprint', Crypt::encrypt($sale->id)), ['reason' => 'Manager review'])
            ->assertRedirect(route('sales.index'))
            ->assertSessionHas('error');
    }

    public function test_delivery_verification_marks_sale_delivered_and_rejects_duplicates(): void
    {
        $user = $this->createUserWithPermissions(['sale-delivery-verify']);
        $sale = $this->createSale(['created_by' => $user->id]);

        $this->actingAs($user)
            ->postJson(route('sales.verify-delivery'), [
                'delivery_code' => $sale->delivery_code,
            ])
            ->assertOk()
            ->assertJsonFragment([
                'status' => 200,
                'sale_no' => $sale->sale_no,
            ]);

        $sale->refresh();

        $this->assertNotNull($sale->delivered_at);
        $this->assertSame($user->id, $sale->delivered_by);
        $this->assertSame(1, SaleReceiptEvent::where('sale_id', $sale->id)->where('event_type', 'delivery')->count());

        $this->actingAs($user)
            ->postJson(route('sales.verify-delivery'), [
                'delivery_code' => $sale->delivery_code,
            ])
            ->assertStatus(409)
            ->assertJsonFragment([
                'status' => 409,
            ]);
    }
}