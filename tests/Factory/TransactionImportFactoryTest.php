<?php

declare(strict_types=1);

namespace Factory;

use App\Factory\TransactionImportFactory;
use App\Service\TransactionImport;
use PHPUnit\Framework\TestCase;

class TransactionImportFactoryTest extends TestCase
{
    private TransactionImportFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new TransactionImportFactory(
            new \ArrayIterator([
                'txt' => $this->createMock(TransactionImport::class),
                'json' => $this->createMock(TransactionImport::class),
            ]),
        );
    }

    public function testGetTransactionImportSourceNotFile(): void
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage('Transactions can only be imported from files at the moment.');

        $this->factory->getTransactionImport('foo');
    }

    public function testGetTransactionImportSourceNotSupported(): void
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage('No transaction importer found for xml');

        $this->factory->getTransactionImport('tests/data/test.xml');
    }

    public function testGetTransactionImportSourceIsFile(): void
    {
        $transactionImport = $this->factory->getTransactionImport('tests/data/test.txt');
        self::assertInstanceOf(TransactionImport::class, $transactionImport);

        $transactionImport = $this->factory->getTransactionImport('tests/data/test.json');
        self::assertInstanceOf(TransactionImport::class, $transactionImport);
    }
}
