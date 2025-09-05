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

declare(ticks=1);

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

require_once __DIR__.'/../vendor/autoload.php';

use ConsoleStyleKit\ConsoleStyleKit;
use ConsoleStyleKit\Elements\BadgeElement;
use ConsoleStyleKit\Elements\BlockquoteElement;
use ConsoleStyleKit\Elements\KeyValueElement;
use ConsoleStyleKit\Elements\LoadingElement;
use ConsoleStyleKit\Elements\RatingElement;
use ConsoleStyleKit\Elements\SeparatorElement;
use ConsoleStyleKit\Elements\TimelineElement;
use ConsoleStyleKit\Enums\BadgeColor;
use ConsoleStyleKit\Enums\BlockquoteType;
use ConsoleStyleKit\Enums\LoadingCharacterSet;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

// Create console style instance
$input = new ArrayInput([]);
$output = new ConsoleOutput();
$style = new ConsoleStyleKit($input, $output);

echo "\n";
$style->title('Console Style Kit - Visual Test');
$style->text('Übersicht aller verfügbaren Style Elements für optische Kontrolle');
$style->newLine();

// =============================================================================
// 1. BLOCKQUOTE ELEMENTS
// =============================================================================

$style->section('📝 Blockquote Elements');

$blockquoteTypes = [
    ['INFO', BlockquoteType::INFO, 'Dies ist eine Information für den Benutzer'],
    ['TIP', BlockquoteType::TIP, 'Hier ist ein hilfreicher Tipp für bessere Ergebnisse'],
    ['IMPORTANT', BlockquoteType::IMPORTANT, 'Wichtiger Hinweis, den Sie beachten sollten'],
    ['WARNING', BlockquoteType::WARNING, 'Warnung: Diese Aktion kann nicht rückgängig gemacht werden'],
    ['CAUTION', BlockquoteType::CAUTION, 'Vorsicht bei der Ausführung dieses Befehls'],
];

foreach ($blockquoteTypes as [$name, $type, $text]) {
    $style->text("🔸 $name Blockquote:");
    BlockquoteElement::create($style, $text, $type)->render();
    $style->newLine();
}

$style->text('🔸 Ohne Typ:');
BlockquoteElement::create($style, 'Einfache Blockquote ohne spezifischen Typ')->render();
$style->newLine();

// =============================================================================
// 2. BADGE ELEMENTS
// =============================================================================

$style->section('🏷️ Badge Elements');

$badgeColors = [
    ['SUCCESS', BadgeColor::GREEN, 'Operation erfolgreich'],
    ['INFO', BadgeColor::BLUE, 'Zusätzliche Information'],
    ['WARNING', BadgeColor::YELLOW, 'Achtung erforderlich'],
    ['ERROR', BadgeColor::RED, 'Fehler aufgetreten'],
    ['NEUTRAL', BadgeColor::GRAY, 'Standardstatus'],
    ['CUSTOM', BadgeColor::MAGENTA, 'Benutzerdefiniert'],
];

foreach ($badgeColors as [$text, $color, $description]) {
    $badge = BadgeElement::create($style, $text, $color);
    $style->text("🔸 $description: $badge");
}

$style->newLine();
$style->text('🔸 Factory Methods:');
$style->text('   Success: '.BadgeElement::success($style, 'COMPLETED'));
$style->text('   Info: '.BadgeElement::info($style, 'PROCESSING'));
$style->text('   Warning: '.BadgeElement::warning($style, 'PENDING'));
$style->text('   Error: '.BadgeElement::error($style, 'FAILED'));
$style->newLine();

// =============================================================================
// 3. RATING ELEMENTS
// =============================================================================

$style->section('⭐ Rating Elements');

$style->text('🔸 Circle Style (ohne Farbe):');
for ($i = 0; $i <= 5; ++$i) {
    $rating = RatingElement::circle($style, 5, $i);
    $style->text("   $i/5: $rating");
}

$style->newLine();
$style->text('🔸 Circle Style (mit Farbe):');
for ($i = 0; $i <= 5; ++$i) {
    $rating = RatingElement::circle($style, 5, $i, true);
    $style->text("   $i/5: $rating");
}

$style->newLine();
$style->text('🔸 Bar Style (ohne Farbe):');
for ($i = 0; $i <= 5; ++$i) {
    $rating = RatingElement::bar($style, 5, $i);
    $style->text("   $i/5: $rating");
}

$style->newLine();
$style->text('🔸 Bar Style (mit Farbe):');
for ($i = 0; $i <= 5; ++$i) {
    $rating = RatingElement::bar($style, 5, $i, true);
    $style->text("   $i/5: $rating");
}

$style->newLine();

// =============================================================================
// 4. SEPARATOR ELEMENTS
// =============================================================================

$style->section('➖ Separator Elements');

$style->text('🔸 Standard Separator:');
SeparatorElement::create($style)->render();

$style->text('🔸 Custom Width (30):');
SeparatorElement::create($style)->setWidth(30)->render();

$style->text('🔸 Custom Character (*):');
SeparatorElement::create($style)->setCharacter('*')->render();

$style->text('🔸 Combination (=, 40):');
SeparatorElement::create($style)->setCharacter('=')->setWidth(40)->render();

$style->newLine();

// =============================================================================
// 5. KEY-VALUE ELEMENTS
// =============================================================================

$style->section('🔑 Key-Value Elements');

$keyValueData = [
    ['Name', 'Console Style Kit'],
    ['Version', '1.0.0'],
    ['Author', 'Konrad Michalik'],
    ['License', 'GPL-3.0-or-later'],
    ['PHP', PHP_VERSION],
];

$style->text('🔸 Standard Key-Value:');
foreach ($keyValueData as [$key, $value]) {
    KeyValueElement::create($style, $key, $value)->render();
}

$style->newLine();
$style->text('🔸 Mit Farben:');
KeyValueElement::create($style, 'Status', 'Active', 'green')->render();
KeyValueElement::create($style, 'Environment', 'Production', 'red')->render();
KeyValueElement::create($style, 'Debug Mode', 'Enabled', 'yellow')->render();

$style->newLine();

// =============================================================================
// 6. TIMELINE ELEMENTS
// =============================================================================

$style->section('📅 Timeline Elements');

$style->text('🔸 Projekt Timeline:');
$events = [
    ['date' => '2025-01-01', 'event' => 'Projekt gestartet'],
    ['date' => '2025-01-15', 'event' => 'Erste Features implementiert'],
    ['date' => '2025-02-01', 'event' => 'Testing Phase begonnen'],
    ['date' => '2025-02-15', 'event' => 'Release vorbereitet'],
    ['date' => '2025-03-01', 'event' => 'Version 1.0 veröffentlicht'],
];

TimelineElement::create($style, $events)->render();
$style->newLine();

$style->text('🔸 Kurze Timeline:');
$shortEvents = [
    ['date' => 'Heute', 'event' => 'Tests gestartet'],
    ['date' => 'Morgen', 'event' => 'Review geplant'],
];

TimelineElement::create($style, $shortEvents)->render();
$style->newLine();

// =============================================================================
// 7. LOADING ELEMENTS
// =============================================================================

$style->section('⏳ Loading Elements');

$style->text('🔸 Character Sets (Static Display):');

$charSets = [
    ['STARS', LoadingCharacterSet::STARS, 'Standard mixed star symbols'],
    ['BRAILLE', LoadingCharacterSet::BRAILLE, 'Smooth braille animation'],
    ['DOTS', LoadingCharacterSet::DOTS, 'Pulsating dots'],
    ['ARROWS', LoadingCharacterSet::ARROWS, 'Rotating arrows'],
    ['BARS', LoadingCharacterSet::BARS, 'Classic rotating bars'],
];

foreach ($charSets as [$name, $charSet, $description]) {
    $loading = LoadingElement::create($style, $description)->setCharacterSet($charSet);
    $style->text("   $name: $loading");
}

$style->newLine();
$style->text('🔸 Mit Farben:');
$colors = ['red', 'green', 'blue', 'yellow', 'magenta', 'cyan'];
foreach ($colors as $color) {
    $loading = LoadingElement::create($style, ucfirst($color).' Loading')->setColor($color);
    $style->text("   $color: $loading");
}

$style->newLine();
$style->text('🔸 Ohne Punkte:');
$loading = LoadingElement::create($style, 'Clean Loading')->hideDots();
$style->text("   No dots: $loading");

$style->newLine();
$style->text('🔸 Live Animation Demo (3 Sekunden):');
$style->loading('Live Demo Animation', 3, 'green', LoadingCharacterSet::BRAILLE);
$style->text('✅ Animation abgeschlossen!');

$style->newLine();

// =============================================================================
// 8. KOMBINIERTE BEISPIELE
// =============================================================================

$style->section('🎨 Kombinierte Beispiele');

$style->text('🔸 Status Dashboard:');
$style->text('System Status: '.BadgeElement::success($style, 'ONLINE'));
$style->text('Performance: '.RatingElement::circle($style, 5, 4, true));
KeyValueElement::create($style, 'Uptime', '99.9%', 'green')->render();
KeyValueElement::create($style, 'Load', '1.2', 'yellow')->render();

$style->newLine();
SeparatorElement::create($style)->setCharacter('=')->render();

$style->text('🔸 Build Report:');
BlockquoteElement::create($style, 'Build erfolgreich abgeschlossen', BlockquoteType::INFO)->render();

$buildStats = [
    ['Tests', '83 passed'],
    ['Coverage', '95.2%'],
    ['Duration', '3.2s'],
    ['Status', (string) BadgeElement::success($style, 'PASSED')],
];

foreach ($buildStats as [$key, $value]) {
    KeyValueElement::create($style, $key, $value)->render();
}

$style->newLine();
SeparatorElement::create($style)->render();

// =============================================================================
// FOOTER
// =============================================================================

$style->success('🎉 Visual Test abgeschlossen!');
$style->text('Alle Style Elements wurden erfolgreich dargestellt.');
$style->newLine();

$style->comment('Tipp: Verwende die Elements als Strings für Logging:');
$style->text('   $badge = BadgeElement::success($style, "OK");');
$style->text('   echo "Status: $badge";');

$style->newLine();
