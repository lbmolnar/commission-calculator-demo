<?php

declare(strict_types=1);

namespace App\DTO;

class Commission
{
    public function __construct(
        private readonly Transaction $transaction,
        private readonly Card $card,
        private readonly float $commission,
    ) {
    }

    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }

    public function getCard(): Card
    {
        return $this->card;
    }

    public function getCommission(): float
    {
        return $this->commission;
    }
}
