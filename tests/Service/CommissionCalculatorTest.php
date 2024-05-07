<?php

declare(strict_types=1);

namespace Service;

use App\DTO\Card;
use App\DTO\Commission;
use App\DTO\Country;
use App\DTO\Transaction;
use App\Factory\TransactionImportFactory;
use App\Service\BinLookupApi;
use App\Service\CommissionCalculator;
use App\Service\ExchangeRateApi;
use App\Service\TransactionImport;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CommissionCalculatorTest extends TestCase
{
    private const string DEFAULT_CURRENCY = 'EUR';
    private const string SOURCE_MOCK = 'SOURCE_MOCK';

    private MockObject|TransactionImport $transactionImportMock;
    private MockObject|TransactionImportFactory $transactionImportFactoryMock;
    private MockObject|BinLookupApi $binLookupApiMock;
    private MockObject|ExchangeRateApi $exchangeRateApiMock;

    private CommissionCalculator $commissionCalculator;

    public function setUp(): void
    {
        $this->transactionImportMock = $this->createMock(TransactionImport::class);
        $this->transactionImportFactoryMock = $this->createMock(TransactionImportFactory::class);
        $this->binLookupApiMock = $this->createMock(BinLookupApi::class);
        $this->exchangeRateApiMock = $this->createMock(ExchangeRateApi::class);

        $this->commissionCalculator = new CommissionCalculator(
            $this->transactionImportFactoryMock,
            $this->binLookupApiMock,
            $this->exchangeRateApiMock,
            self::DEFAULT_CURRENCY
        );
    }

    public function testCalculateCommissionWithTransactions(): void
    {
        $this->transactionImportMock
            ->expects(self::once())
            ->method('import')
            ->with(self::SOURCE_MOCK)
            ->willReturn([
                new Transaction('12345', 100, 'EUR'),
                new Transaction('23456', 200, 'USD'),
                new Transaction('34567', 300, 'EUR'),
                new Transaction('45678', 400, 'RON'),
            ]);
        $this->transactionImportFactoryMock
            ->expects(self::once())
            ->method('getTransactionImport')
            ->with(self::SOURCE_MOCK)
            ->willReturn($this->transactionImportMock);
        $this->binLookupApiMock
            ->expects(self::exactly(4))
            ->method('getCardDetails')
            ->willReturn(
                new Card(12345, 'credit', 'visa', new Country('Italy', 'IT')),
                new Card(23456, 'debit', 'mastercard', new Country('Hong Kong', 'HK')),
                new Card(34567, 'credit', 'visa', new Country('Austria', 'AT')),
                new Card(45678, 'credit', 'visa', new Country('Romania', 'RO')),
            );
        $this->exchangeRateApiMock
            ->expects(self::exactly(2))
            ->method('getExchangeRates')
            ->willReturn(
                ['USD' => 2.5],
                ['RON' => 4.5]
            );

        $commissions = $this->commissionCalculator->calculateCommission(self::SOURCE_MOCK);

        self::assertCount(4, $commissions);

        $expectedCommissions = [1, 1.6, 3, 0.8888888888888888];
        foreach ($commissions as $key => $commission) {
            self::assertInstanceOf(Commission::class, $commission);
            self::assertEquals($expectedCommissions[$key], $commission->getCommission());
        }
    }
}
