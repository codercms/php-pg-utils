<?php
/**
 * File: DateRange.php
 * Author: Dmitry K. <coder1994@gmail.com>
 * Date: 2022-11-30
 * Copyright (c) 2022
 */

declare(strict_types=1);

namespace Codercms\PgUtils\Range;

use DateTimeImmutable;
use DateTimeInterface;
use JsonSerializable;

class DateRange implements JsonSerializable
{
    public function __construct(
        public ?DateTimeInterface $start,
        public ?DateTimeInterface $end,
        public bool $lowerInc,
        public bool $upperInc,
    ) {
    }

    public function isNull(): bool
    {
        return $this->start === null && $this->end === null;
    }

    public function getStart(): ?DateTimeInterface
    {
        if ($this->start === null) {
            return null;
        }

        if (!$this->lowerInc) {
            return DateTimeImmutable::createFromInterface($this->start)
                ->add(InternalInterval::getDayInterval());
        }

        return $this->start;
    }

    public function getEnd(): ?DateTimeInterface
    {
        if ($this->end === null) {
            return null;
        }

        if (!$this->upperInc) {
            return DateTimeImmutable::createFromInterface($this->end)
                ->sub(InternalInterval::getDayInterval());
        }

        return $this->end;
    }

    public function jsonSerialize(): array
    {
        return [
            'start' => $this->getStart()?->format('Y-m-d'),
            'end' => $this->getEnd()?->format('Y-m-d'),
        ];
    }
}
