<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Card;

interface BinLookupApi
{
    public function getCardDetails(string $bin): ?Card;
}
