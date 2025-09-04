<?php

declare(strict_types=1);

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

use EliasHaeussler\PhpCsFixerConfig\Config;
use EliasHaeussler\PhpCsFixerConfig\Package;
use EliasHaeussler\PhpCsFixerConfig\Rules;
use Symfony\Component\Finder;

$header = Rules\Header::create(
    'console-style-kit',
    Package\Type::ComposerPlugin,
    Package\Author::create('Konrad Michalik', 'hej@konradmichalik.dev'),
    Package\CopyrightRange::from(2025),
    Package\License::GPL3OrLater,
);

return Config::create()
    ->withRule($header)
    ->withRule(
        Rules\RuleSet::fromArray(
            KonradMichalik\PhpDocBlockHeaderFixer\Generators\DocBlockHeader::create(
                [
                    'author' => 'Konrad Michalik <hej@konradmichalik.dev>',
                    'license' => 'GPL-3.0-or-later',
                    'package' => 'ConsoleStyleKit',
                ],
            )->__toArray(),
        ),
    )
    ->withFinder(static fn (Finder\Finder $finder) => $finder->in(__DIR__)->notName('run-tests.php'))
    ->registerCustomFixers([
        new KonradMichalik\PhpDocBlockHeaderFixer\Rules\DocBlockHeaderFixer(),
    ])
;
