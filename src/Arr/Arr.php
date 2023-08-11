<?php
/**
 * File: Arr.php
 * Author: Dmitry K. <coder1994@gmail.com>
 * Date: 2022-11-30
 * Copyright (c) 2022
 */

declare(strict_types=1);

namespace Codercms\PgUtils\Arr;

use InvalidArgumentException;
use Stringable;

use function array_map;
use function explode;
use function implode;
use function str_replace;
use function substr;

class Arr
{
    private static ?ArrayParser $parser = null;

    /**
     * Suitable for all types, even unsafe ones
     * It's safe to pass values with commas, braces and quotes
     *
     * @param array<scalar|Stringable|null> $items
     * @param bool $allowNulls
     * @return string
     */
    public static function asTextSafeArr(array $items, bool $allowNulls = false): string
    {
        $parts = array_map(static function (string|null $item) use ($allowNulls): string {
            if ($item === null) {
                if (!$allowNulls) {
                    throw new InvalidArgumentException("Null value not allowed");
                }

                return 'NULL';
            }

            $count = 0;

            $prepared = str_replace(
                ['"', "'", '{', '}', ','],
                ['\\"', "\\'", '\\{', '\\}', '\\,'],
                $item,
                $count
            );

            // Quote value
            if ($count > 0) {
                return "\"{$prepared}\"";
            }

            return $prepared;
        }, $items);

        return '{' . implode(',', $parts) . '}';
    }

    /**
     * Suitable for int, float/decimal, uuid
     * It's UNSAFE to pass values with commas, braces, quotes
     *
     * @param array<string|Stringable|null> $items
     * @param bool $allowNulls
     * @return string
     */
    public static function asTextUnsafeArr(array $items, bool $allowNulls = false): string
    {
        $parts = array_map(static function (string|null $item) use ($allowNulls): string {
            if ($item === null) {
                if (!$allowNulls) {
                    throw new InvalidArgumentException("Null value not allowed");
                }

                return 'NULL';
            }

            return (string)$item;
        }, $items);

        return '{' . implode(',', $parts) . '}';
    }

    /**
     * For Postgres bool array
     *
     * @param array<bool|null> $items
     * @param bool $allowNulls
     * @return string
     */
    public static function asBoolArr(array $items, bool $allowNulls = false): string
    {
        $parts = array_map(static function (bool|null $item) use ($allowNulls): string {
            if ($item === null) {
                if (!$allowNulls) {
                    throw new InvalidArgumentException("Null value not allowed");
                }

                return 'NULL';
            }

            return $item === false ? 'f' : 't';
        }, $items);

        return '{' . implode(',', $parts) . '}';
    }

    /**
     * Suitable for int, float/decimal, uuid
     * It's UNSAFE to pass values with commas, braces, quotes
     *
     * Will not work on multidimensional arrays!!!
     *
     * @param string $strArr
     * @return array<string>
     * @experimental
     */
    public static function decodeAsUnsafeTextArr(string $strArr): array
    {
        if (!str_starts_with($strArr, '{') || !str_ends_with($strArr, '}')) {
            throw new InvalidArgumentException("Bad postgres array passed: {$strArr}");
        }

        $strArr = substr($strArr, 1, -1);
        if ($strArr === '') {
            return [];
        }

        return explode(',', $strArr);
    }

    /**
     * Suitable for all types, even unsafe ones
     * It's safe to pass values with commas, braces and quotes
     *
     * Will not work on multidimensional arrays!!!
     *
     * @param string $strArr
     * @return array<string>
     * @experimental
     */
    public static function decodeAsSafeTextArr(string $strArr): array
    {
        if (!str_starts_with($strArr, '{') || !str_ends_with($strArr, '}')) {
            throw new InvalidArgumentException("Bad postgres array passed: {$strArr}");
        }

        static::$parser ??= new ArrayParser();

        return static::$parser->parse(
            $strArr,
            static function ($val) {
                return $val;
            },
        );
    }

    /**
     * Will not work on multidimensional arrays!!!
     *
     * @param string $strArr
     * @return array<int|null>
     * @experimental
     */
    public static function decodeAsIntArr(string $strArr): array
    {
        return array_map(
            static function (string $row): ?int {
                if ($row === 'NULL') {
                    return null;
                }

                return (int)$row;
            },
            self::decodeAsUnsafeTextArr($strArr),
        );
    }

    /**
     * Will not work on multidimensional arrays!!!
     *
     * @param string $strArr
     * @return array<float|null>
     * @experimental
     */
    public static function decodeAsFloatArr(string $strArr): array
    {
        return array_map(
            static function (string $row): ?float {
                if ($row === 'NULL') {
                    return null;
                }

                return (float)$row;
            },
            self::decodeAsUnsafeTextArr($strArr),
        );
    }

    /**
     * Will not work on multidimensional arrays!!!
     *
     * @param string $strArr
     * @return array<bool|null>
     * @experimental
     */
    public static function decodeAsBoolArr(string $strArr): array
    {
        return array_map(
            static function (string $row): ?bool {
                if ($row === 'NULL') {
                    return null;
                }

                return $row === 't';
            },
            self::decodeAsUnsafeTextArr($strArr),
        );
    }
}
