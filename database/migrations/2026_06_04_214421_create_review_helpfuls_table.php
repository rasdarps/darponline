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
        Schema::create('review_helpfuls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained('product_reviews')->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->string('session_token')->nullable();
            $table->boolean('is_helpful')->default(true);
            $table->timestamps();
            
            $table->unique(['review_id', 'customer_id', 'session_token']);
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_helpfuls');
    }
};
