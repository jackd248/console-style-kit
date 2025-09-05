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
$style->loading('Processing data', 3); // 3 seconds
```

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

// Loading animations
$loading = $style->loading('Processing'); // Manual control
// ... do work
$loading->stop();

// Or with fixed duration
$style->loading('Uploading file', 5, 'green', LoadingCharacterSet::DOTS);
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

## 🎬 Loading Animations

The LoadingElement provides animated progress indicators with flexible control:

### Basic Usage

The LoadingElement supports **three different animation modes**:

#### 1. Fixed Duration Mode (Automatic)
```php
// Runs for exactly 3 seconds with full animation
$style->loading('Processing data', 3);
```

#### 2. Manual Control Mode
```php
// Start animation, call update() in your work loop, then stop
$loading = $style->loading('Uploading file');
while ($working) {
    // Your work here
    doWork();
    $loading->update(); // Keep animation running
}
$loading->stop();
```

#### 3. Auto-Update Mode (Background)
```php
// Add declare(ticks=1) at the top of your PHP file for auto-update to work
declare(ticks=1);

// Automatic updates during code execution (uses tick functions)
$loading = $style->loading('Processing')
    ->enableAutoUpdate()
    ->start(); // Important: call start() after enableAutoUpdate()

// Animation runs automatically during any code execution
// Every PHP statement triggers the animation update
for ($i = 0; $i < 1000; $i++) {
    performComplexCalculation($i); // Animation updates automatically
    processData($i);               // No manual update() calls needed
    saveResults($i);               // Animation continues seamlessly
}
$loading->stop();
```

> **Note**: Auto-update mode requires `declare(ticks=1)` at the top of your PHP file to enable tick functions. The animation will update automatically on every PHP statement execution, providing smooth animation during long-running operations without any manual intervention. Always call `->start()` after `->enableAutoUpdate()` to ensure proper initialization.

### Customization Options

```php
use ConsoleStyleKit\Enums\LoadingCharacterSet;

// With color and custom character set
$style->loading('Downloading', 5, 'green', LoadingCharacterSet::DOTS);

// Using object-oriented API
$loading = LoadingElement::create($style, 'Syncing data')
    ->setColor('blue')
    ->setCharacterSet(LoadingCharacterSet::ARROWS)
    ->hideDots() // Remove trailing dots
    ->render(); // Start animation

// Stop when done
$loading->stop();
```

### Available Character Sets

- **STARS**: `·•*✲✳✶✱✻✽` (Default, mixed star symbols)
- **BRAILLE**: `⠋⠙⠹⠸⠼⠴⠦⠧⠇⠏` (Smooth animation)
- **DOTS**: `●○●○●○●○` (Pulsating dots)
- **ARROWS**: `←↖↑↗→↘↓↙` (Rotating arrows)
- **BARS**: `|/-\` (Classic rotating bars)

### Factory Methods

```php
// Quick creation with specific character sets
$loading = LoadingElement::stars($style, 'Processing')->render();
$loading = LoadingElement::braille($style, 'Loading')->render();
$loading = LoadingElement::dots($style, 'Working')->setColor('cyan')->render();
```

## 🧑‍💻 Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.


## ⭐ License

Licensed under GPL-3.0-or-later. See [LICENSE](LICENSE.md) for details.
