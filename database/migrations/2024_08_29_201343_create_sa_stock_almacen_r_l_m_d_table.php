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
        Schema::create('sa_stock_almacen_r_l_m_d', function (Blueprint $table) {
            $table->id();
            $table->string('co_alma');
            $table->string('co_art');
            $table->string('tipo');
            $table->float('stock');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sa_stock_almacen_r_l_m_d');
    }
};
