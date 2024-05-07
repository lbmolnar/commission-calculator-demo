<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Transaction;

interface TransactionImport
{
    /**
     * @return Transaction[]
     */
    public function import(string $importSource): array;

    public static function supportedImportSource(): string;
}
