<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Relacionamentos
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained(
            ); // Se apagar o produto, mantemos o pedido por histórico (sem cascade)

            // Dados Financeiros
            $table->bigInteger('transaction_amount');
            $table->string('payment_method')->nullable(); // pix, credit_card

            // Controle de Estado e Integração
            $table->string('status')->default('pending'); // pending, approved, rejected, refunded
            $table->uuid('external_reference')->unique()->index(); // Indexado para busca rápida no Webhook
            $table->string('mercadopago_id')->nullable()->index(); // ID do MP para estornos

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
