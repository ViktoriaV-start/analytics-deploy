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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('g_number')->nullable();
            $table->date('date')->nullable();
            $table->date('last_change_date')->nullable();
            $table->string('supplier_article')->nullable();
            $table->string('tech_size')->nullable();
            $table->string('barcode')->nullable();
            $table->float('total_price')->nullable();
            $table->integer('discount_percent')->nullable();
            $table->tinyInteger('is_supply')->nullable();
            $table->tinyInteger('is_realization')->nullable();
            $table->float('promo_code_discount')->nullable();
            $table->string('warehouse_name')->nullable();
            $table->string('country_name')->nullable();
            $table->string('oblast_okrug_name')->nullable();
            $table->string('region_name')->nullable();
            $table->unsignedBigInteger('income_id')->nullable();
            $table->string('sale_id')->nullable();
            $table->unsignedBigInteger('odid')->nullable();
            $table->integer('spp')->nullable();
            $table->float('for_pay')->nullable();
            $table->float('finished_price')->nullable();
            $table->float('price_with_disc')->nullable();
            $table->unsignedBigInteger('nm_id')->nullable();
            $table->string('subject')->nullable();
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->tinyInteger('is_storno')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
