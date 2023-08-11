<?php
/**
 * File: InternalInterval.php
 * Author: Dmitry K. <coder1994@gmail.com>
 * Date: 2022-11-30
 * Copyright (c) 2022
 */

declare(strict_types=1);

namespace Codercms\PgUtils\Range;

use DateInterval;

/**
 * @internal
 */
final class InternalInterval
{
    private static ?DateInterval $day;

    public static function getDayInterval(): DateInterval
    {
        return self::$day ??= new \DateInterval('P1D');
    }
}
