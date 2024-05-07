<?php

declare(strict_types=1);

namespace App\DTO;

readonly class Card
{
    public function __construct(
        private int $bin,
        private string $type,
        private string $brand,
        private Country $country,
    ) {
    }

    public function getBin(): int
    {
        return $this->bin;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }
}
