# Console Style Kit

A PHP package for creating consistent and beautiful console output with various styling elements.

> [!WARNING]
> This package is in early development and may change significantly.

## 🔥 Installation

```bash
composer require konradmichalik/console-style-kit
```

## ⚡ Usage

### Simple API

```php
use ConsoleStyleKit\ConsoleStyleKit;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

$style = new ConsoleStyleKit(new ArrayInput([]), new ConsoleOutput());

$style->blockquote('Important message', 'INFO');
$style->rating(5, 4);
$style->showBadge('SUCCESS');
$style->loading('Processing data', 3);
$loading = $style->loading('Processing');
// ... do work
$loading->stop();
```

> [!Note]
> Auto-update mode requires `declare(ticks=1)` at the top of your PHP file to enable tick functions. The animation will update automatically on every PHP statement execution, providing smooth animation during long-running operations without any manual intervention. Always call `->start()` after `->enableAutoUpdate()` to ensure proper initialization.

### Object-Oriented API

```php
use ConsoleStyleKit\Elements\BlockquoteElement;
use ConsoleStyleKit\Elements\RatingElement;
use ConsoleStyleKit\Elements\BadgeElement;
use ConsoleStyleKit\Elements\LoadingElement;
use ConsoleStyleKit\Enums\BlockquoteType;
use ConsoleStyleKit\Enums\RatingStyle;
use ConsoleStyleKit\Enums\BadgeColor;
use ConsoleStyleKit\Enums\LoadingCharacterSet;

// Fluent interface with Enums
$style->createBlockquote()
    ->setText('Enhanced blockquote')
    ->setType(BlockquoteType::WARNING)
    ->render();

// Static factory methods
RatingElement::circle($style, 5, 4, true)->render();
BadgeElement::create($style, 'CUSTOM')->setColor(BadgeColor::BLUE)->render();
```

### Output Examples

```shell
# Blockquote with type
┃ WARNING
┃ This is a warning message

# Rating display
● ● ● ● ○ (4/5)

# Badge
 SUCCESS

# Timeline
● 2024-01-01 ─ Project Start
│
● 2024-06-01 ─ Milestone Reached
│
● 2024-12-01 ─ Project Complete

# Loading Animation
⠋ Processing data …
```

### Available Elements

- **Blockquotes**: INFO, TIP, IMPORTANT, WARNING, CAUTION
- **Ratings**: Circle and bar styles with colorful options
- **Badges**: Various colors and semantic types
- **Separators**: Customizable width and characters
- **Key-Value pairs**: With arrows and colors
- **Timeline**: Multi-event display with custom connectors
- **Loading animations**: Animated progress indicators with multiple styles and character sets

## 🧑‍💻 Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.


## ⭐ License

Licensed under GPL-3.0-or-later. See [LICENSE](LICENSE.md) for details.
