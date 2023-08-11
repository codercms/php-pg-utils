<?php
/**
 * File: DateRangeParserTest.php
 * Author: Dmitry K. <coder1994@gmail.com>
 * Date: 2022-11-30
 * Copyright (c) 2022
 */

declare(strict_types=1);

namespace Tests\Range;

use Erg\PgUtils\Range\DateRangeParser;
use Erg\PgUtils\Range\DateRange;
use PHPUnit\Framework\TestCase;

class DateRangeParserTest extends TestCase
{
    public function testDecodeDateRange(): void
    {
        $range = DateRangeParser::decodeDateRange('[2010-12-31,2022-11-30]');

        self::assertTrue($range->lowerInc);
        self::assertTrue($range->upperInc);
        self::assertEquals(new \DateTime('2010-12-31'), $range->start);
        self::assertEquals(new \DateTime('2022-11-30'), $range->end);
    }

    public function testAsDateRange(): void
    {
        $range = new DateRange(
            start: new \DateTimeImmutable('2022-01-01'),
            end: new \DateTimeImmutable('2022-10-01'),
            lowerInc: true,
            upperInc: true,
        );
        $res = DateRangeParser::asDateRange($range);
        self::assertSame('[2022-01-01,2022-10-01]', $res);

        $range = new DateRange(
            start: null,
            end: new \DateTimeImmutable('2022-10-01'),
            lowerInc: true,
            upperInc: true,
        );
        $res = DateRangeParser::asDateRange($range);
        self::assertSame('[,2022-10-01]', $res);

        $range = new DateRange(
            start: new \DateTimeImmutable('2022-01-01'),
            end: null,
            lowerInc: true,
            upperInc: true,
        );
        $res = DateRangeParser::asDateRange($range);
        self::assertSame('[2022-01-01,]', $res);
    }
}
