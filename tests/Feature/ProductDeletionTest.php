<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Unit;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class ProductDeletionTest extends TestCase
{
    use DatabaseTransactions;

    public function test_product_with_purchase_detail_cannot_be_deleted(): void
    {
        $this->withoutMiddleware();

        $category = Category::create(['name' => 'Test Category ' . uniqid()]);
        $unit = Unit::create(['name' => 'Test Unit ' . uniqid()]);
        $product = Product::create([
            'name' => 'Test Product ' . uniqid(),
            'category_id' => $category->id,
            'unit_id' => $unit->id,
            'quantity' => 0,
            'stock_level' => 0,
            'cost_price' => 0,
            'selling_price' => 0,
        ]);

        $purchase = Purchase::create([
            'purchase_no' => 'PO-' . uniqid(),
            'date' => now()->toDateString(),
        ]);

        PurchaseDetail::create([
            'purchase_id' => $purchase->id,
            'date' => now()->toDateString(),
            'category_id' => $category->id,
            'product_id' => $product->id,
            'buying_qty' => 1,
            'unit_cost_price' => 10,
            'buying_price' => 10,
            'unit_id' => $unit->id,
        ]);

        $response = $this->deleteJson(route('products.destroy', Crypt::encrypt($product->id)));

        $response->assertStatus(422)
            ->assertJsonPath('status', 422)
            ->assertJsonFragment(['message' => "Product '{$product->name}' cannot be deleted because it has purchase or sales records."]);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'deleted_at' => null]);
    }
}
