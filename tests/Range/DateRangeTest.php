<?php
/**
 * File: DateRangeTest.php
 * Author: Dmitry K. <coder1994@gmail.com>
 * Date: 2022-11-30
 * Copyright (c) 2022
 */

declare(strict_types=1);

namespace Tests\Range;

use Erg\PgUtils\Range\DateRange;
use PHPUnit\Framework\TestCase;

class DateRangeTest extends TestCase
{
    public function testDefault(): void
    {
        $range = new DateRange(
            start: new \DateTimeImmutable('2022-01-01'),
            end: new \DateTimeImmutable('2022-10-10'),
            lowerInc: true,
            upperInc: false,
        );

        self::assertEquals(new \DateTimeImmutable('2022-01-01'), $range->getStart());
        self::assertEquals(new \DateTimeImmutable('2022-10-09'), $range->getEnd());
    }

    public function testNonIncStart(): void
    {
        $range = new DateRange(
            start: new \DateTimeImmutable('2022-01-01'),
            end: new \DateTimeImmutable('2022-10-10'),
            lowerInc: false,
            upperInc: false,
        );

        self::assertEquals(new \DateTimeImmutable('2022-01-02'), $range->getStart());
        self::assertEquals(new \DateTimeImmutable('2022-10-09'), $range->getEnd());
    }
}
