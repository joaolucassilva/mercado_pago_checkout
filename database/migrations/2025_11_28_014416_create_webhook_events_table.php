<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('webhook_events', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->index(); // 'mercadopago', 'stripe'
            $table->string('type')->default('unknown'); // 'payment.created', etc
            $table->json('payload');
            $table->json('headers')->nullable();
            $table->timestamp('processed_at')->nullable()->index();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_events');
    }
};
