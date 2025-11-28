<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

readonly class Money
{
    public function __construct(
        public int $amountInCents,
        public string $currency = 'BRL',
    ) {
        if ($amountInCents < 0) {
            throw new \InvalidArgumentException('Amount cannot be negative');
        }
    }

    public static function fromCents(int $amountInCents, string $currency = 'BRL'): self
    {
        return new self($amountInCents, $currency);
    }

    public static function fromFloat(float $amount, string $currency = 'BRL'): self
    {
        return new self((int)round($amount * 100), $currency);
    }

    public function toFloat(): float
    {
        return $this->amountInCents / 100;
    }

    public function equals(Money $other): bool
    {
        return $this->amountInCents === $other->amountInCents && $this->currency === $other->currency;
    }

}
