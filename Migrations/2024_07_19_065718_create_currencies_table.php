<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Product\Models\Currency;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(Currency::getDb(), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->integer('number')->unique();
            $table->double('rate')->default(1);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
        Currency::query()->create([
            'name' => '$',
            'code' => 'USD',
            'number' => '840',
            'status' => 1,
            'rate' => 1,
        ]);
        Currency::query()->create([
            'name' => '€',
            'code' => 'EUR',
            'number' => '978',
            'status' => 1,
            'rate' => 1,
        ]);
        Currency::query()->create([
            'name' => '₴',
            'code' => 'UAH',
            'number' => '980',
            'status' => 1,
            'rate' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Currency::getDb());
    }
};
