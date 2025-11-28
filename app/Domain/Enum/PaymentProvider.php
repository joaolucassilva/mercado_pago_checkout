<?php

declare(strict_types=1);

namespace App\Domain\Enum;

enum PaymentProvider: string
{
    case MERCADO_PAGO = 'mercadopago';
    case STRIPE = 'stripe';
}
