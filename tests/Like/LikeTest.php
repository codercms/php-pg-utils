<?php

declare(strict_types=1);

namespace Tests\Like;

use Erg\PgUtils\Like\Like;
use PHPUnit\Framework\TestCase;

class LikeTest extends TestCase
{
    public function testEscape(): void
    {
        $str = Like::escape('Hello, %');
        self::assertSame('Hello, \\%', $str);

        $str = Like::escape('Hello, _');
        self::assertSame('Hello, \\_', $str);

        $str = Like::escape('Hello, \\  %  _');
        self::assertSame('Hello, \\\\  \\%  \\_', $str);
    }

    public function testEscapeEmptyString(): void
    {
        $str = Like::escape('');
        self::assertSame('', $str);
    }
}
