<?php

declare(strict_types=1);

namespace Service;

use App\DTO\Transaction;
use App\Service\TextFileTransactionImport;
use PHPUnit\Framework\TestCase;

class TextFileTransactionImportTest extends TestCase
{
    private TextFileTransactionImport $transactionImport;

    protected function setUp(): void
    {
        $this->transactionImport = new TextFileTransactionImport();
    }

    public function testSupportedImportSource(): void
    {
        self::assertEquals('txt', TextFileTransactionImport::supportedImportSource());
        self::assertNotEquals('json', TextFileTransactionImport::supportedImportSource());
    }

    public function testImport(): void
    {
        $expectedTransactions = [
            [
                'bin' => '12345',
                'amount' => 100,
                'currency' => 'EUR',
            ],
            [
                'bin' => '23456',
                'amount' => 200,
                'currency' => 'JPY',
            ],
            [
                'bin' => '34567',
                'amount' => 300,
                'currency' => 'GBP',
            ],
            [
                'bin' => '45678',
                'amount' => 400,
                'currency' => 'RON',
            ],
        ];
        $transactions = $this->transactionImport->import('tests/data/test.txt');

        self::assertCount(4, $transactions);

        foreach ($transactions as $key => $transaction) {
            self::assertInstanceOf(Transaction::class, $transaction);
            self::assertEquals($expectedTransactions[$key]['bin'], $transaction->getBin());
            self::assertEquals($expectedTransactions[$key]['amount'], $transaction->getAmount());
            self::assertEquals($expectedTransactions[$key]['currency'], $transaction->getCurrency());
        }
    }
}
