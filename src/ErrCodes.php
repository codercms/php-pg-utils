<?php
/**
 * File: ErrCodes.php
 * Author: Dmitry K. <coder1994@gmail.com>
 * Date: 2023-02-28
 * Copyright (c) 2023
 */

declare(strict_types=1);

namespace Codercms\PgUtils;

/**
 * Коды ошибок при работе с PostgreSQL
 */
enum ErrCodes: string
{
    /**
     * Код ошибки при возникновении нарушения уникальности данных в Postgres
     */
    case UNIQUE_VIOLATION_CODE = '23505';

    /**
     * Код ошибки при возникновении нарушения CHECK constraint в Postgres
     */
    case CHECK_VIOLATION_CODE = '23514';

    /**
     * Код ошибки при возникновении нарушения EXCLUDE Constraint в Postgres
     */
    case EXCL_CONSTRAINT_VIOLATION_CODE = '23P01';

    /**
     * Код ошибки при возникновении нарушения Foreign Key Constraint в Postgres
     */
    case FK_VIOLATION_CODE = '23503';
}
