<?php

declare(strict_types=1);

namespace App\Factory;

use App\Service\TransactionImport;
use Ds\Map;
use RuntimeException;
use SplFileInfo;

class TransactionImportFactory
{
    /**
     * @var Map<string, TransactionImport>
     */
    private Map $transactionImporters;

    /**
     * @param iterable<TransactionImport> $transactionImporters
     */
    public function __construct(iterable $transactionImporters)
    {
        $this->transactionImporters = new Map(iterator_to_array($transactionImporters));
    }

    public function getTransactionImport(string $importSource): TransactionImport
    {
        $file = new SplFileInfo($importSource);

        if (false === $file->isFile()) {
            throw new RuntimeException('Transactions can only be imported from files at the moment.');
        }

        $transactionImporter = $this->transactionImporters->get($file->getExtension(), null);

        if (null === $transactionImporter) {
            throw new RuntimeException(
                \sprintf('No transaction importer found for %s', $file->getExtension())
            );
        }

        return $transactionImporter;
    }
}
