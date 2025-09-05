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

use ConsoleStyleKit\Contracts\StyleElementInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * AbstractStyleElement.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-3.0-or-later
 */
abstract class AbstractStyleElement implements StyleElementInterface, \Stringable
{
    public function __construct(protected SymfonyStyle $style) {}

    public function toString(): string
    {
        // Create a temporary BufferedOutput to capture the rendered output
        $tempOutput = new \Symfony\Component\Console\Output\BufferedOutput();
        $tempStyle = new SymfonyStyle(
            new \Symfony\Component\Console\Input\ArrayInput([]),
            $tempOutput,
        );

        // Create a temporary instance with the BufferedOutput
        $tempElement = clone $this;
        $tempElement->style = $tempStyle;
        $tempElement->render();

        return $tempOutput->fetch();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    protected function getTerminalWidth(): int
    {
        if (function_exists('exec')) {
            $output = [];
            exec('tput cols 2>/dev/null', $output);
            if (!empty($output[0]) && is_numeric($output[0])) {
                return (int) $output[0];
            }
        }

        return 80; // fallback
    }

    abstract public function render(): void;
}
