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

// Mock Symfony classes
namespace Symfony\Component\Console\Input {
    if (!class_exists(\Symfony\Component\Console\Input\ArrayInput::class)) {
        class ArrayInput
        {
            public function __construct($input = []) {}
        }
    }
}

namespace Symfony\Component\Console\Output {
    if (!class_exists(\Symfony\Component\Console\Output\BufferedOutput::class)) {
        class BufferedOutput
        {
            private $output = '';

            public function writeln($text)
            {
                $this->output .= $text."\n";
            }

            public function write($text)
            {
                $this->output .= $text;
            }

            public function fetch()
            {
                $result = $this->output;
                $this->output = '';

                return $result;
            }
        }
    }
}

namespace Symfony\Component\Console\Style {
    if (!class_exists(\Symfony\Component\Console\Style\SymfonyStyle::class)) {
        class SymfonyStyle
        {
            public function __construct($input, protected $output)
            {
            }

            public function writeln($text)
            {
                $this->output->writeln($text);
            }
        }
    }
}

namespace {

// Load dependencies in correct order
require_once __DIR__.'/../src/Contracts/StyleElementInterface.php';
require_once __DIR__.'/../src/Elements/AbstractStyleElement.php';
require_once __DIR__.'/../src/Enums/BlockquoteType.php';
require_once __DIR__.'/../src/Enums/RatingStyle.php';
require_once __DIR__.'/../src/Enums/BadgeColor.php';
require_once __DIR__.'/Unit/TestStyleElement.php';
require_once __DIR__.'/../src/ConsoleStyleKit.php';
require_once __DIR__.'/../src/Elements/BlockquoteElement.php';
require_once __DIR__.'/../src/Elements/RatingElement.php';
require_once __DIR__.'/../src/Elements/BadgeElement.php';
require_once __DIR__.'/../src/Elements/SeparatorElement.php';
require_once __DIR__.'/../src/Elements/KeyValueElement.php';
require_once __DIR__.'/../src/Elements/TimelineElement.php';

use ConsoleStyleKit\ConsoleStyleKit;
use ConsoleStyleKit\Elements\BadgeElement;
use ConsoleStyleKit\Elements\BlockquoteElement;
use ConsoleStyleKit\Elements\RatingElement;
use ConsoleStyleKit\Enums\BlockquoteType;
use ConsoleStyleKit\Tests\Unit\TestStyleElement;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

class SimpleTestRunner
{
    private int $passed = 0;
    private int $failed = 0;

    public function run(): void
    {
        echo "Console Style Kit - Simple Test Runner\n";
        echo "======================================\n\n";

        $this->testAbstractStyleElement();
        $this->testBlockquoteElement();
        $this->testRatingElement();
        $this->testBadgeElement();
        $this->testConsoleStyleKit();

        echo "\n".str_repeat('=', 50)."\n";
        echo "Test Results:\n";
        echo "✓ Passed: {$this->passed}\n";
        if ($this->failed > 0) {
            echo "✗ Failed: {$this->failed}\n";
        } else {
            echo "All tests passed! 🎉\n";
        }
    }

    private function testAbstractStyleElement(): void
    {
        echo "Testing AbstractStyleElement...\n";

        $output = new BufferedOutput();
        $style = new SymfonyStyle(new ArrayInput([]), $output);

        $element = new TestStyleElement($style);
        $element->render();

        $result = $output->fetch();
        $this->assert(str_contains((string) $result, 'test output'), 'AbstractStyleElement render test');

        $width = $element->getTerminalWidthPublic();
        $this->assert(is_int($width) && $width >= 80, 'Terminal width test');
    }

    private function testBlockquoteElement(): void
    {
        echo "Testing BlockquoteElement...\n";

        $output = new BufferedOutput();
        $style = new SymfonyStyle(new ArrayInput([]), $output);

        // Test simple blockquote
        $element = new BlockquoteElement($style);
        $element->setText('Test message')->render();

        $result = $output->fetch();
        $this->assert(str_contains((string) $result, 'Test message'), 'Simple blockquote test');
        $this->assert(str_contains((string) $result, '|'), 'Blockquote pipe character test');

        // Test with type
        $element = BlockquoteElement::create($style, 'Info test', BlockquoteType::INFO);
        $element->render();

        $result = $output->fetch();
        $this->assert(str_contains((string) $result, 'INFO'), 'Blockquote type test');
    }

    private function testRatingElement(): void
    {
        echo "Testing RatingElement...\n";

        $output = new BufferedOutput();
        $style = new SymfonyStyle(new ArrayInput([]), $output);

        // Test circle rating
        $element = RatingElement::circle($style, 5, 3);
        $element->render();

        $result = $output->fetch();
        $this->assert(str_contains((string) $result, '●'), 'Circle rating filled test');
        $this->assert(str_contains((string) $result, '○'), 'Circle rating empty test');

        // Test bar rating
        $element = RatingElement::bar($style, 5, 2);
        $element->render();

        $result = $output->fetch();
        $this->assert(str_contains((string) $result, '#'), 'Bar rating filled test');
        $this->assert(str_contains((string) $result, '-'), 'Bar rating empty test');
    }

    private function testBadgeElement(): void
    {
        echo "Testing BadgeElement...\n";

        $output = new BufferedOutput();
        $style = new SymfonyStyle(new ArrayInput([]), $output);

        $element = BadgeElement::success($style, 'SUCCESS');
        $element->render();

        $result = $output->fetch();
        $this->assert(str_contains((string) $result, 'SUCCESS'), 'Badge text test');
    }

    private function testConsoleStyleKit(): void
    {
        echo "Testing ConsoleStyleKit integration...\n";

        $output = new BufferedOutput();
        $kit = new ConsoleStyleKit(new ArrayInput([]), $output);

        // Test legacy API
        $kit->blockquote('Legacy test');
        $result = $output->fetch();
        $this->assert(str_contains((string) $result, 'Legacy test'), 'Legacy API test');

        // Test fluent API
        $kit->showBadge('FLUENT')->showSeparator();
        $result = $output->fetch();
        $this->assert(str_contains((string) $result, 'FLUENT'), 'Fluent API test');

        // Test factory methods
        $element = $kit->createBlockquote();
        $this->assert($element instanceof BlockquoteElement, 'Factory method test');
    }

    private function assert(bool $condition, string $testName): void
    {
        if ($condition) {
            echo "  ✓ {$testName}\n";
            ++$this->passed;
        } else {
            echo "  ✗ {$testName}\n";
            ++$this->failed;
        }
    }
}

$runner = new SimpleTestRunner();
$runner->run();

}
