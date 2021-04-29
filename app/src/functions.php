<?php

declare(strict_types=1);

namespace App\functions;

/**
 * Function to calculate sum of each dice value in Yatzy
 *
 * @return int
 */
function sumDiceValue(array $savedDices, int $number): int
{
    $count = array_keys($savedDices, $number);

    $sum = 0;

    foreach ($count as $key) {
        $sum += $savedDices[$key];
    }

    return $sum;
}
