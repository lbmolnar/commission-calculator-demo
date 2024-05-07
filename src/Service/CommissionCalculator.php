<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Commission;
use App\Factory\TransactionImportFactory;

readonly class CommissionCalculator
{
    public function __construct(
        private TransactionImportFactory $transactionImportFactory,
        private BinLookupApi $binLookupApi,
        private ExchangeRateApi $exchangeRateApi,
        private string $defaultCurrency,
    ) {
    }

    /**
     * @param string $source
     * @return Commission[]
     */
    public function calculateCommission(string $source): array
    {
        $commissions = [];
        $transactions = $this->transactionImportFactory->getTransactionImport($source)->import($source);

        foreach ($transactions as $transaction) {
            $card = $this->binLookupApi->getCardDetails($transaction->getBin());

            $amount = $transaction->getAmount();
            if ($this->defaultCurrency !== $transaction->getCurrency()) {
                $exchangeRates = $this->exchangeRateApi->getExchangeRates($this->defaultCurrency);
                $amount = $amount / $exchangeRates[$transaction->getCurrency()];
            }

            $commissions[] = new Commission(
                $transaction,
                $card,
                $amount * (true === $card->getCountry()->isEurope() ? 0.01 : 0.02)
            );
        }

        return $commissions;
    }
}
