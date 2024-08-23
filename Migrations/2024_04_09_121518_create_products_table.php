<?php

use App\Models\StaticPage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Product\Models\Product;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(Product::getDb(), function (Blueprint $table) {
            $table->id()->index();
            $table->string('name');
            $table->string('slug')->unique()->index();
            $table->integer('sorting')->default(0);
            $table->boolean('status')->default(1);
            $table->string('sku')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->double('price')->nullable();
            $table->integer('views')->default(0);
            $table->json('images')->nullable();
            Product::timestampFields($table);
        });
        StaticPage::createSystemPage('Catalog', 'catalog',path:'catalog-component');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Product::getDb());
    }
};
