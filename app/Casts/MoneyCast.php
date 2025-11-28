<?php

declare(strict_types=1);

namespace App\Casts;

use App\Domain\ValueObjects\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class MoneyCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): Money
    {
        return Money::fromCents((int)$value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): int
    {
        if (!$value instanceof Money) {
            throw new InvalidArgumentException('The value must be an instance of Money.');
        }

        return $value->amountInCents;
    }
}
