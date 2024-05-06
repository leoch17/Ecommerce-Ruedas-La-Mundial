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
        Schema::create('discount_coupons', function (Blueprint $table) {
            $table->id();

            // Código cupon de descuento
            $table->string('code');

            // El nombre del código del cupón de descuento legible por el ser humano
            $table->string('name')->nullable();

            // La descripción del cupón - No es necesaria
            $table->text('description')->nullable();

            // El máximo uso que tiene este cupón 
            $table->integer('max_uses')->nullable();

            // Cuántas veces un usuario puede usar este ticket
            $table->integer('max_uses_user')->nullable();

            // Si el cupón es un procentaje o un precio fijo
            $table->enum('type',['percent','fixed'])->default('fixed');

            // El importe a descontar en función del tipo
            $table->double('discount_amount', 10,2);

            // El importe a descontar en función del tipo
            $table->double('min_amount', 10,2)->nullable();

            $table->integer('status')->default(1);

            // Cuándo empieza el cupón
            $table->timestamp('starts_at')->nullable();

            // Cuándo finaliza el cupón
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_coupons');
    }
};
