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

namespace ConsoleStyleKit;

use ConsoleStyleKit\Elements\BadgeElement;
use ConsoleStyleKit\Elements\BlockquoteElement;
use ConsoleStyleKit\Elements\KeyValueElement;
use ConsoleStyleKit\Elements\RatingElement;
use ConsoleStyleKit\Elements\SeparatorElement;
use ConsoleStyleKit\Elements\TimelineElement;
use ConsoleStyleKit\Enums\BadgeColor;
use ConsoleStyleKit\Enums\BlockquoteType;
use ConsoleStyleKit\Enums\RatingStyle;
use Symfony\Component\Console\Style\SymfonyStyle;


/**
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-3.0-or-later
 *
 * @package ConsoleStyleKit
 */
class ConsoleStyleKit extends SymfonyStyle
{
    // Legacy constants for backward compatibility
    public const INFO = 'INFO';
    public const TIP = 'TIP';
    public const IMPORTANT = 'IMPORTANT';
    public const WARNING = 'WARNING';
    public const CAUTION = 'CAUTION';

    // Legacy methods for backward compatibility
    public function blockquote(string $text, ?string $type = null): void
    {
        $enumType = $type ? BlockquoteType::from($type) : null;
        BlockquoteElement::create($this, $text, $enumType)->render();
    }

    public function rating(int $max, int $current, string $style = 'circle', bool $colorful = false): void
    {
        $enumStyle = RatingStyle::from($style);
        if (RatingStyle::CIRCLE === $enumStyle) {
            RatingElement::circle($this, $max, $current, $colorful)->render();
        } else {
            RatingElement::bar($this, $max, $current, $colorful)->render();
        }
    }

    public function badge(string $text, string $color = 'gray'): void
    {
        $enumColor = BadgeColor::from($color);
        BadgeElement::create($this, $text, $enumColor)->render();
    }

    public function separator(): void
    {
        SeparatorElement::create($this)->render();
    }

    public function keyValue(string $key, string $value, ?string $keyColor = null): void
    {
        KeyValueElement::create($this, $key, $value, $keyColor)->render();
    }

    /**
     * @param array<int, array{date: string, event: string}> $events
     */
    public function timeline(array $events): void
    {
        TimelineElement::create($this, $events)->render();
    }

    // New OOP API methods
    public function createBlockquote(): BlockquoteElement
    {
        return new BlockquoteElement($this);
    }

    public function createRating(): RatingElement
    {
        return new RatingElement($this);
    }

    public function createBadge(): BadgeElement
    {
        return new BadgeElement($this);
    }

    public function createSeparator(): SeparatorElement
    {
        return new SeparatorElement($this);
    }

    public function createKeyValue(): KeyValueElement
    {
        return new KeyValueElement($this);
    }

    public function createTimeline(): TimelineElement
    {
        return new TimelineElement($this);
    }

    // Fluent helper methods
    public function showBlockquote(string $text, ?string $type = null): self
    {
        $this->blockquote($text, $type);

        return $this;
    }

    public function showRating(int $max, int $current, string $style = 'circle', bool $colorful = false): self
    {
        $this->rating($max, $current, $style, $colorful);

        return $this;
    }

    public function showBadge(string $text, string $color = 'gray'): self
    {
        $this->badge($text, $color);

        return $this;
    }

    public function showSeparator(): self
    {
        $this->separator();

        return $this;
    }

    public function showKeyValue(string $key, string $value, ?string $keyColor = null): self
    {
        $this->keyValue($key, $value, $keyColor);

        return $this;
    }

    /**
     * @param array<int, array{date: string, event: string}> $events
     */
    public function showTimeline(array $events): self
    {
        $this->timeline($events);

        return $this;
    }
}
