<?php
/**
 * File: RangeParser.php
 * Author: Dmitry K. <coder1994@gmail.com>
 * Date: 2022-11-30
 * Copyright (c) 2022
 */

declare(strict_types=1);

namespace Erg\PgUtils\Range;

use InvalidArgumentException;

use function count;
use function explode;
use function strlen;
use function substr;

class RangeParser
{
    public const LOWER_NON_INC = '(';
    public const LOWER_INC = '[';

    public const UPPER_NON_INC = ')';
    public const UPPER_INC = ']';

    public static function parseRangeStruct(string $rangeVal): RangeStruct
    {
        $len = strlen($rangeVal);
        if ($len < 3) {
            throw new InvalidArgumentException("Wrong range (at least 3 chars needed): {$rangeVal}");
        }

        $start = $rangeVal[0];
        $end = $rangeVal[$len - 1];

        $lowerInc = match ($start) {
            self::LOWER_INC => true,
            self::LOWER_NON_INC => false,
            default => throw new InvalidArgumentException("Wrong range (lower bound): {$rangeVal}"),
        };

        $upperInc = match ($end) {
            self::UPPER_INC => true,
            self::UPPER_NON_INC => false,
            default => throw new InvalidArgumentException("Wrong range (upper bound): {$rangeVal}"),
        };

        $rangeItemsPart = substr($rangeVal, 1, -1);
        $parts = explode(',', $rangeItemsPart, 2);
        if (count($parts) !== 2) {
            throw new InvalidArgumentException("Wrong range (need 2 parts): {$rangeVal}");
        }

        return new RangeStruct(
            start: $parts[0],
            end: $parts[1],
            lowerInc: $lowerInc,
            upperInc: $upperInc,
        );
    }
}
