<?php
/**
 * File: RangeParserTest.php
 * Author: Dmitry K. <coder1994@gmail.com>
 * Date: 2022-11-30
 * Copyright (c) 2022
 */

declare(strict_types=1);

namespace Tests\Range;

use Erg\PgUtils\Range\RangeParser;
use PHPUnit\Framework\TestCase;

class RangeParserTest extends TestCase
{
    public function testParseRangeStruct(): void
    {
        $ranges = [
            '(1,2)' => ['l_inc' => false, 'u_inc' => false, 'p1' => '1', 'p2' => '2'],
            '(1,2]' => ['l_inc' => false, 'u_inc' => true, 'p1' => '1', 'p2' => '2'],
            '[1,2)' => ['l_inc' => true, 'u_inc' => false, 'p1' => '1', 'p2' => '2'],
            '[1,2]' => ['l_inc' => true, 'u_inc' => true, 'p1' => '1', 'p2' => '2'],

            '(1,)' => ['l_inc' => false, 'u_inc' => false, 'p1' => '1', 'p2' => ''],
            '(1,]' => ['l_inc' => false, 'u_inc' => true, 'p1' => '1', 'p2' => ''],
            '[1,)' => ['l_inc' => true, 'u_inc' => false, 'p1' => '1', 'p2' => ''],
            '[1,]' => ['l_inc' => true, 'u_inc' => true, 'p1' => '1', 'p2' => ''],

            '(,1)' => ['l_inc' => false, 'u_inc' => false, 'p1' => '', 'p2' => '1'],
            '(,1]' => ['l_inc' => false, 'u_inc' => true, 'p1' => '', 'p2' => '1'],
            '[,1)' => ['l_inc' => true, 'u_inc' => false, 'p1' => '', 'p2' => '1'],
            '[,1]' => ['l_inc' => true, 'u_inc' => true, 'p1' => '', 'p2' => '1'],
        ];

        foreach ($ranges as $rawRange => ['l_inc' => $lInc, 'u_inc' => $uInc, 'p1' => $p1, 'p2' => $p2]) {
            $range = RangeParser::parseRangeStruct($rawRange);

            self::assertSame($lInc, $range->lowerInc);
            self::assertSame($uInc, $range->upperInc);
            self::assertSame($p1, $range->start);
            self::assertSame($p2, $range->end);
        }
    }
}
