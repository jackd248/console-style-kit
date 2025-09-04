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

namespace ConsoleStyleKit\Tests\Unit;

use ConsoleStyleKit\ConsoleStyleKit;
use ConsoleStyleKit\Elements\BadgeElement;
use ConsoleStyleKit\Elements\BlockquoteElement;
use ConsoleStyleKit\Elements\KeyValueElement;
use ConsoleStyleKit\Elements\RatingElement;
use ConsoleStyleKit\Elements\SeparatorElement;
use ConsoleStyleKit\Elements\TimelineElement;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;


/**
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-3.0-or-later
 *
 * @package ConsoleStyleKit
 */
class ConsoleStyleKitTest extends TestCase
{
    private ConsoleStyleKit $style;
    private BufferedOutput $output;

    protected function setUp(): void
    {
        $this->output = new BufferedOutput();
        $this->style = new ConsoleStyleKit(new ArrayInput([]), $this->output);
    }

    public function testLegacyConstants(): void
    {
        $this->assertSame('INFO', ConsoleStyleKit::INFO);
        $this->assertSame('TIP', ConsoleStyleKit::TIP);
        $this->assertSame('IMPORTANT', ConsoleStyleKit::IMPORTANT);
        $this->assertSame('WARNING', ConsoleStyleKit::WARNING);
        $this->assertSame('CAUTION', ConsoleStyleKit::CAUTION);
    }

    public function testLegacyBlockquote(): void
    {
        $this->style->blockquote('Test message');
        $output = $this->output->fetch();

        $this->assertStringContainsString('Test message', $output);
        $this->assertStringContainsString('|', $output);
    }

    public function testLegacyRating(): void
    {
        $this->style->rating(5, 3);
        $output = $this->output->fetch();

        $this->assertStringContainsString('●', $output);
    }

    public function testLegacyBadge(): void
    {
        $this->style->badge('TEST', 'blue');
        $output = $this->output->fetch();

        $this->assertStringContainsString('TEST', $output);
    }

    public function testLegacySeparator(): void
    {
        $this->style->separator();
        $output = $this->output->fetch();

        $this->assertStringContainsString('-', $output);
    }

    public function testLegacyKeyValue(): void
    {
        $this->style->keyValue('Key', 'Value');
        $output = $this->output->fetch();

        $this->assertStringContainsString('Key', $output);
        $this->assertStringContainsString('Value', $output);
    }

    public function testLegacyTimeline(): void
    {
        $events = [['date' => '2024-01-01', 'event' => 'Test']];
        $this->style->timeline($events);
        $output = $this->output->fetch();

        $this->assertStringContainsString('2024-01-01', $output);
        $this->assertStringContainsString('Test', $output);
    }

    public function testCreateBlockquote(): void
    {
        $element = $this->style->createBlockquote();
        $this->assertInstanceOf(BlockquoteElement::class, $element);
    }

    public function testCreateRating(): void
    {
        $element = $this->style->createRating();
        $this->assertInstanceOf(RatingElement::class, $element);
    }

    public function testCreateBadge(): void
    {
        $element = $this->style->createBadge();
        $this->assertInstanceOf(BadgeElement::class, $element);
    }

    public function testCreateSeparator(): void
    {
        $element = $this->style->createSeparator();
        $this->assertInstanceOf(SeparatorElement::class, $element);
    }

    public function testCreateKeyValue(): void
    {
        $element = $this->style->createKeyValue();
        $this->assertInstanceOf(KeyValueElement::class, $element);
    }

    public function testCreateTimeline(): void
    {
        $element = $this->style->createTimeline();
        $this->assertInstanceOf(TimelineElement::class, $element);
    }

    public function testFluentHelperMethods(): void
    {
        $result = $this->style
            ->showBlockquote('Test', 'INFO')
            ->showRating(5, 3)
            ->showBadge('TEST')
            ->showSeparator()
            ->showKeyValue('Key', 'Value')
            ->showTimeline([['date' => '2024-01-01', 'event' => 'Test']]);

        $this->assertSame($this->style, $result);

        $output = $this->output->fetch();
        $this->assertStringContainsString('Test', $output);
        $this->assertStringContainsString('INFO', $output);
        $this->assertStringContainsString('●', $output);
        $this->assertStringContainsString('TEST', $output);
        $this->assertStringContainsString('-', $output);
        $this->assertStringContainsString('Key', $output);
        $this->assertStringContainsString('Value', $output);
        $this->assertStringContainsString('2024-01-01', $output);
    }

    public function testBackwardCompatibility(): void
    {
        // Test that old API still works exactly the same
        $this->style->blockquote('Legacy test', ConsoleStyleKit::WARNING);
        $this->style->rating(5, 4, 'bar', true);
        $this->style->badge('OLD', 'red');

        $output = $this->output->fetch();

        $this->assertStringContainsString('Legacy test', $output);
        $this->assertStringContainsString('WARNING', $output);
        $this->assertStringContainsString('#', $output);
        $this->assertStringContainsString('OLD', $output);
    }
}
