<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRatesApi implements ExchangeRateApi
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly string $exchangeRatesApiKey,
    ) {
    }

    public function getExchangeRates(string $base): array
    {
        try {
            $response = $this->client->request(
                'GET',
                'https://api.apilayer.com/exchangerates_data/latest?base=' . $base,
                [
                    'headers' => [
                        'Content-Type: text/plain',
                        'apikey: ' . $this->exchangeRatesApiKey
                    ]
                ]
            );

            return $response->toArray()['rates'];
        } catch (\Throwable) {
            // log error here
            return [];
        }
    }
}
