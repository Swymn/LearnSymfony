<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AmountExtension extends AbstractExtension {

    public function getFilters(): array {
        return [
            new TwigFilter('amount', [$this, 'amount'])
        ];
    }

    public function amount(int $value, string $currency = ' €', string $decimal_sep = ',', string $thousands_sep = ' '): string {
        $price = $value / 100;
        $price = number_format($price, 2, $decimal_sep, $thousands_sep);

        return $price . $currency;
    }
}