<?php

declare(strict_types=1);

namespace App\DTO;

use App\Enum\EuropeAlpha2;

readonly class Country
{
    public function __construct(
        private string $name,
        private string $alpha2,
        private ?string $continent = null,
    ) {
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function getAlpha2(): string
    {
        return $this->alpha2;
    }

    public function getContinent(): ?string
    {
        return $this->continent;
    }

    public function isEurope(): bool
    {
        return 'europe' === $this->continent || null !== EuropeAlpha2::tryFrom($this->alpha2);
    }
}
