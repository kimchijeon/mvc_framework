<?php

declare(strict_types=1);

namespace App\Functions;

/**
 * Function to calculate sum of each dice value in Yatzy
 *
 * @return int
 */
function sumDiceValue(array $saveddices, int $number): int
{
    $count = array_keys($saveddices, $number);

    $sum = 0;

    foreach ($count as $key) {
        $sum += $saveddices[$key];
    }

    return $sum;
}
