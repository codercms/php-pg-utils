<?php
/**
 * File: RangeStruct.php
 * Author: Dmitry K. <coder1994@gmail.com>
 * Date: 2022-11-30
 * Copyright (c) 2022
 */

declare(strict_types=1);

namespace Codercms\PgUtils\Range;

class RangeStruct
{
    public function __construct(
        public string $start,
        public string $end,
        public bool $lowerInc,
        public bool $upperInc,
    ) {
    }
}
