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

use ConsoleStyleKit\Enums\BlockquoteType;
use Symfony\Component\Console\Style\SymfonyStyle;


/**
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-3.0-or-later
 *
 * @package ConsoleStyleKit
 */
class BlockquoteElement extends AbstractStyleElement
{
    // Legacy constants for backward compatibility
    public const INFO = 'INFO';
    public const TIP = 'TIP';
    public const IMPORTANT = 'IMPORTANT';
    public const WARNING = 'WARNING';
    public const CAUTION = 'CAUTION';

    private string $text;
    private ?BlockquoteType $type = null;

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function setType(BlockquoteType|string|null $type): self
    {
        $this->type = $type instanceof BlockquoteType ? $type : ($type ? BlockquoteType::from($type) : null);

        return $this;
    }

    public function render(): void
    {
        $this->style->writeln('');

        if (null === $this->type) {
            $this->style->writeln('<fg=gray>|</> '.$this->text);
        } else {
            $color = $this->type->getColor();
            $bold = $this->type->isBold() ? ';options=bold' : '';

            $this->style->writeln("<fg={$color}{$bold}>|</> <fg={$color}{$bold}>{$this->type->value}</>");
            $this->style->writeln("<fg={$color}>|</> {$this->text}");
        }

        $this->style->writeln('');
    }

    public static function create(SymfonyStyle $style, string $text, BlockquoteType|string|null $type = null): self
    {
        return (new self($style))
            ->setText($text)
            ->setType($type);
    }
}
