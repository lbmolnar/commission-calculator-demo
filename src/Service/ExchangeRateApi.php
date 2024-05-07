<?php

declare(strict_types=1);

namespace App\Service;

interface ExchangeRateApi
{
    /**
     * @return array<string, float>
     */
    public function getExchangeRates(string $base): array;
}
