<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Product\Models\Product;
use Modules\Product\Models\ProductReview;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(ProductReview::getDb(), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(Product::class)->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('rating');
            $table->string('image')->nullable();
            $table->mediumText('comment');
            $table->integer('status')->default(1);
            Product::timestampFields($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(ProductReview::getDb());
    }
};
