<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
            if (!Schema::hasColumn('products', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('products', 'slug')) {
                $table->string('slug')->unique()->after('name');
            }
        
           
            // if (!Schema::hasColumn('products', 'sale_price')) {
            //     $table->decimal('sale_price', 10, 2)->nullable()->after('price');
            // }
            // if (!Schema::hasColumn('products', 'wholesale_price')) {
            //     $table->decimal('wholesale_price', 10, 2)->nullable()->after('sale_price');
            // }
            // if (!Schema::hasColumn('products', 'cost_price')) {
            //     $table->decimal('cost_price', 10, 2)->nullable()->after('wholesale_price');
            // }
            // if (!Schema::hasColumn('products', 'featured_image')) {
            //     $table->string('featured_image')->nullable()->after('image');
            // }
            if (!Schema::hasColumn('products', 'video_url')) {
                $table->string('video_url')->nullable();
            }
            if (!Schema::hasColumn('products', 'weight')) {
                $table->decimal('weight', 8, 2)->nullable(); // in kg
            }
            if (!Schema::hasColumn('products', 'length')) {
                $table->decimal('length', 8, 2)->nullable(); // in cm
            }
            if (!Schema::hasColumn('products', 'width')) {
                $table->decimal('width', 8, 2)->nullable();
            }
            if (!Schema::hasColumn('products', 'height')) {
                $table->decimal('height', 8, 2)->nullable();
            }
            if (!Schema::hasColumn('products', 'sku')) {
                $table->string('sku')->unique()->nullable();
            }
            // if (!Schema::hasColumn('products', 'barcode')) {
            //     $table->string('barcode')->nullable();
            // }
            if (!Schema::hasColumn('products', 'brand_id')) {
                $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null');
            }
        
            if (!Schema::hasColumn('products', 'min_order_quantity')) {
                $table->integer('min_order_quantity')->default(1);
            }
            if (!Schema::hasColumn('products', 'max_order_quantity')) {
                $table->integer('max_order_quantity')->nullable();
            }
            if (!Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false);
            }
            if (!Schema::hasColumn('products', 'is_on_sale')) {
                $table->boolean('is_on_sale')->default(false);
            }
            if (!Schema::hasColumn('products', 'is_new')) {
                $table->boolean('is_new')->default(false);
            }
            if (!Schema::hasColumn('products', 'is_online')) {
                $table->boolean('is_online')->default(false);
            }
            if (!Schema::hasColumn('products', 'tags')) {
                $table->json('tags')->nullable();
            }
            if (!Schema::hasColumn('products', 'seo_title')) {
                $table->string('seo_title')->nullable();
            }
            if (!Schema::hasColumn('products', 'seo_description')) {
                $table->text('seo_description')->nullable();
            }
         
            // Indexes
            $table->index('slug');
            $table->index('is_online');
            $table->index('is_featured');
            $table->index('brand_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
