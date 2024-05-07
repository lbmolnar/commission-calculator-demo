<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Card;
use App\DTO\Country;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HandyApiBinLookupApi implements BinLookupApi
{
    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    public function getCardDetails(string $bin): ?Card
    {
        $response = $this->client->request(
            'GET',
            'https://data.handyapi.com/bin/' . $bin,
        );

        try {
            $content = $response->toArray();

            return new Card(
                (int)$bin,
                $content['Type'],
                $content['Scheme'],
                new Country(
                    $content['Country']['Name'],
                    strtoupper($content['Country']['A2']),
                    strtolower($content['Country']['Cont']),
                )
            );
        } catch (\Throwable) {
            return null;
        }
    }
}
