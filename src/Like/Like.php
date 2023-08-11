<?php

declare(strict_types=1);

namespace Erg\PgUtils\Like;

use function str_replace;

class Like
{
    public static function escape(string $str): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $str);
    }
}
