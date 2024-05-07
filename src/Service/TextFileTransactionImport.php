<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Transaction;
use SplFileObject;

class TextFileTransactionImport implements TransactionImport
{
    private const string SUPPORTED_IMPORT_SOURCE = 'txt';

    /**
     * @return Transaction[]
     */
    public function import(string $importSource): array
    {
        $transactions = [];
        $file = new SplFileObject($importSource, "r");
        $file->rewind();

        while (true !== $file->eof()) {
            $line = json_decode($file->fgets(), true);

            if (null === $line) {
                // log here unreadable lines
                continue;
            }

            $transactions[] = new Transaction(
                $line['bin'],
                (float) $line['amount'],
                strtoupper($line['currency']),
            );
        }

        return $transactions;
    }

    public static function supportedImportSource(): string
    {
        return self::SUPPORTED_IMPORT_SOURCE;
    }
}
