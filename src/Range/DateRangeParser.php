<?php
/**
 * File: DateRangeParser.php
 * Author: Dmitry K. <coder1994@gmail.com>
 * Date: 2022-11-30
 * Copyright (c) 2022
 */

declare(strict_types=1);

namespace Codercms\PgUtils\Range;

use DateTimeImmutable;

class DateRangeParser
{
    public static function decodeDateRange(string $rangeVal): DateRange
    {
        $range = RangeParser::parseRangeStruct($rangeVal);

        $start = null;
        $end = null;

        if ($range->start !== '') {
            $start = new DateTimeImmutable($range->start);
        }

        if ($range->end !== '') {
            $end = new DateTimeImmutable($range->end);
        }

        return new DateRange(
            start: $start,
            end: $end,
            lowerInc: $range->lowerInc,
            upperInc: $range->upperInc,
        );
    }

    public static function asDateRange(DateRange $range): string
    {
        $str = $range->lowerInc ? RangeParser::LOWER_INC : RangeParser::LOWER_NON_INC;
        $str .= $range->start?->format('Y-m-d');
        $str .= ',';
        $str .= $range->end?->format('Y-m-d');
        $str .= $range->upperInc ? RangeParser::UPPER_INC : RangeParser::UPPER_NON_INC;

        return $str;
    }
}
