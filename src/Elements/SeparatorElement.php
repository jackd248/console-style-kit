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

use Symfony\Component\Console\Style\SymfonyStyle;


/**
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-3.0-or-later
 *
 * @package ConsoleStyleKit
 */
class SeparatorElement extends AbstractStyleElement
{
    private string $character = '-';
    private ?int $width = null;

    public function setCharacter(string $character): self
    {
        $this->character = $character;

        return $this;
    }

    public function setWidth(?int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function render(): void
    {
        $width = $this->width ?? $this->getTerminalWidth();
        $this->style->writeln(str_repeat($this->character, $width));
    }

    public static function create(SymfonyStyle $style): self
    {
        return new self($style);
    }

    public static function line(SymfonyStyle $style, ?int $width = null): self
    {
        return (new self($style))
            ->setCharacter('-')
            ->setWidth($width);
    }

    public static function dots(SymfonyStyle $style, ?int $width = null): self
    {
        return (new self($style))
            ->setCharacter('.')
            ->setWidth($width);
    }

    public static function equals(SymfonyStyle $style, ?int $width = null): self
    {
        return (new self($style))
            ->setCharacter('=')
            ->setWidth($width);
    }
}
