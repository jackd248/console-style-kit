<?php

/*
 * This file is part of the Composer plugin "console-style-kit".
 *
 * Copyright (C) 2025 Konrad Michalik <hej@konradmichalik.dev>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

namespace ConsoleStyleKit\Elements;

use ConsoleStyleKit\Enums\RatingStyle;
use Symfony\Component\Console\Style\SymfonyStyle;


/**
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-3.0-or-later
 *
 * @package ConsoleStyleKit
 */
class RatingElement extends AbstractStyleElement
{
    // Legacy constants for backward compatibility
    public const STYLE_CIRCLE = 'circle';
    public const STYLE_BAR = 'bar';

    private int $max;
    private int $current;
    private RatingStyle $displayStyle = RatingStyle::CIRCLE;
    private bool $colorful = false;

    public function setRating(int $max, int $current): self
    {
        $this->max = $max;
        $this->current = $current;

        return $this;
    }

    public function setStyle(RatingStyle|string $style): self
    {
        $this->displayStyle = $style instanceof RatingStyle ? $style : RatingStyle::from($style);

        return $this;
    }

    public function setColorful(bool $colorful): self
    {
        $this->colorful = $colorful;

        return $this;
    }

    public function render(): void
    {
        match ($this->displayStyle) {
            RatingStyle::CIRCLE => $this->renderCircle(),
            RatingStyle::BAR => $this->renderBar(),
        };
    }

    private function renderCircle(): void
    {
        $circles = [];

        for ($i = 0; $i < $this->max; ++$i) {
            if ($i < $this->current) {
                if ($this->colorful) {
                    $color = $this->getRatingColor($this->current, $this->max);
                    $circles[] = "<fg={$color}>●</>";
                } else {
                    $circles[] = '<fg=white>●</>';
                }
            } else {
                $circles[] = '<fg=gray>○</>';
            }
        }

        $this->style->writeln(implode(' ', $circles));
    }

    private function renderBar(): void
    {
        $filled = str_repeat('#', $this->current);
        $empty = str_repeat('-', $this->max - $this->current);

        if ($this->colorful) {
            $color = $this->getRatingColor($this->current, $this->max);
            $this->style->writeln("[<fg={$color}>{$filled}</><fg=gray>{$empty}</>]");
        } else {
            $this->style->writeln("[<fg=white>{$filled}</><fg=gray>{$empty}</>]");
        }
    }

    private function getRatingColor(int $current, int $max): string
    {
        $percentage = $current / $max;

        if ($percentage >= 0.8) {
            return 'green';
        } elseif ($percentage >= 0.5) {
            return 'yellow';
        } else {
            return 'red';
        }
    }

    public static function create(SymfonyStyle $style, int $max, int $current): self
    {
        return (new self($style))->setRating($max, $current);
    }

    public static function circle(SymfonyStyle $style, int $max, int $current, bool $colorful = false): self
    {
        return self::create($style, $max, $current)
            ->setStyle(RatingStyle::CIRCLE)
            ->setColorful($colorful);
    }

    public static function bar(SymfonyStyle $style, int $max, int $current, bool $colorful = false): self
    {
        return self::create($style, $max, $current)
            ->setStyle(RatingStyle::BAR)
            ->setColorful($colorful);
    }
}
