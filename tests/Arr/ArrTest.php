<?php
/**
 * File: ArrTest.php
 * Author: Dmitry K. <coder1994@gmail.com>
 * Date: 2022-11-30
 * Copyright (c) 2022
 */

declare(strict_types=1);

namespace Tests\Arr;

use Erg\PgUtils\Arr\Arr;
use PHPUnit\Framework\TestCase;

class ArrTest extends TestCase
{
    public function testAsTextSafeArr(): void
    {
        $arr = Arr::asTextSafeArr(['1', '2', 3, 3.14]);
        self::assertSame('{1,2,3,3.14}', $arr);

        $arr = Arr::asTextSafeArr(['Hello, Dmitry! {name}"quote1\'quote2']);
        self::assertSame('{"Hello\, Dmitry! \{name\}\"quote1\\\'quote2"}', $arr);

        $json = '{"hello": "world", "test": "Dmitry\'s", "arr": [1,2,3]}';
        $arr = Arr::asTextSafeArr([$json]);
        self::assertSame('{"\{\"hello\": \"world\"\, \"test\": \"Dmitry\\\'s\"\, \"arr\": [1\,2\,3]\}"}', $arr);

        $failed = false;
        try {
            Arr::asTextSafeArr([null]);
        } catch (\InvalidArgumentException) {
            $failed = true;
        }
        self::assertTrue($failed);

        $arr = Arr::asTextSafeArr([1, 2, null, 3], true);
        self::assertSame('{1,2,NULL,3}', $arr);
    }

    public function testAsTextUnsafeArr(): void
    {
        $arr = Arr::asTextUnsafeArr(['1', '2', 3, 3.14]);
        self::assertSame('{1,2,3,3.14}', $arr);

        $arr = Arr::asTextUnsafeArr(['Hello, Dmitry! {name}"quote1\'quote2']);
        self::assertSame('{Hello, Dmitry! {name}"quote1\'quote2}', $arr);

        $failed = false;
        try {
            Arr::asTextUnsafeArr([null]);
        } catch (\InvalidArgumentException) {
            $failed = true;
        }
        self::assertTrue($failed);

        $arr = Arr::asTextUnsafeArr([1, 2, null, 3], true);
        self::assertSame('{1,2,NULL,3}', $arr);
    }

    public function testAsBoolArr(): void
    {
        $arr = Arr::asBoolArr([false, true]);
        self::assertSame('{f,t}', $arr);
    }

    public function testDecodeAsUnsafeTextArr(): void
    {
        $arr = Arr::decodeAsUnsafeTextArr('{}');
        self::assertSame([], $arr);

        $arr = Arr::decodeAsUnsafeTextArr('{1}');
        self::assertSame(['1'], $arr);

        $arr = Arr::decodeAsUnsafeTextArr('{1,2,3,Dmitry,hey,hop}');
        self::assertSame(['1', '2', '3', 'Dmitry', 'hey', 'hop'], $arr);
    }

    public function testDecodeAsSafeTextArr(): void
    {
        $arr = Arr::decodeAsSafeTextArr('{}');
        self::assertSame([], $arr);

        $arr = Arr::decodeAsSafeTextArr('{1}');
        self::assertSame(['1'], $arr);

        $arr = Arr::decodeAsSafeTextArr('{1,2,3,Dmitry,"hey\,hop"}');
        self::assertSame(['1', '2', '3', 'Dmitry', 'hey,hop'], $arr);
    }

    public function testDecodeAsIntArr(): void
    {
        $arr = Arr::decodeAsIntArr('{}');
        self::assertSame([], $arr);

        $arr = Arr::decodeAsIntArr('{1}');
        self::assertSame([1], $arr);

        $arr = Arr::decodeAsIntArr('{1,2,3}');
        self::assertSame([1, 2, 3], $arr);
    }

    public function testDecodeAsFloatArr(): void
    {
        $arr = Arr::decodeAsFloatArr('{}');
        self::assertSame([], $arr);

        $arr = Arr::decodeAsFloatArr('{1.4}');
        self::assertSame([1.4], $arr);

        $arr = Arr::decodeAsFloatArr('{1,2,3.14}');
        self::assertSame([1., 2., 3.14], $arr);
    }

    public function testDecodeAsBoolArr(): void
    {
        $arr = Arr::decodeAsBoolArr('{}');
        self::assertSame([], $arr);

        $arr = Arr::decodeAsBoolArr('{f}');
        self::assertSame([false], $arr);

        $arr = Arr::decodeAsBoolArr('{f,t}');
        self::assertSame([false, true], $arr);
    }
}
