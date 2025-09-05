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
 * TimelineElement.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-3.0-or-later
 */
class TimelineElement extends AbstractStyleElement
{
    /**
     * @var array<int, array{date: string, event: string}>
     */
    private array $events = [];
    private string $connector = '●';
    private string $line = '-';

    /**
     * @param array<int, array{date: string, event: string}> $events
     */
    public function setEvents(array $events): self
    {
        $this->events = $events;

        return $this;
    }

    public function addEvent(string $date, string $event): self
    {
        $this->events[] = ['date' => $date, 'event' => $event];

        return $this;
    }

    public function setConnector(string $connector): self
    {
        $this->connector = $connector;

        return $this;
    }

    public function setLine(string $line): self
    {
        $this->line = $line;

        return $this;
    }

    public function render(): void
    {
        if (empty($this->events)) {
            return;
        }

        $width = $this->getTerminalWidth();
        $eventCount = count($this->events);

        if (1 === $eventCount) {
            $this->renderSingleEvent();

            return;
        }

        $this->renderMultipleEvents($width, $eventCount);
    }

    private function renderSingleEvent(): void
    {
        $this->style->writeln('      '.$this->events[0]['date']);
        $this->style->writeln('|'.str_repeat($this->line, 10).$this->connector.str_repeat($this->line, 10).'|');
        $this->style->writeln('      '.$this->events[0]['event']);
    }

    private function renderMultipleEvents(int $width, int $eventCount): void
    {
        // Reduce width to prevent overflow, reserve space for borders
        $availableWidth = max(40, $width - 10);
        $segmentWidth = (int) ($availableWidth / $eventCount);

        $dates = '';
        $timeline = '|';
        $eventTexts = '';

        foreach ($this->events as $index => $event) {
            $date = $event['date'];
            $eventText = $event['event'];

            // Truncate long text to fit
            $date = substr($date, 0, $segmentWidth - 2);
            $eventText = substr($eventText, 0, $segmentWidth - 2);

            if (0 === $index) {
                $dates .= str_pad($date, $segmentWidth, ' ', STR_PAD_RIGHT);
                $timeline .= str_repeat($this->line, $segmentWidth - 1).$this->connector;
                $eventTexts .= str_pad($eventText, $segmentWidth, ' ', STR_PAD_RIGHT);
            } elseif ($index === $eventCount - 1) {
                $dates .= str_pad($date, $segmentWidth, ' ', STR_PAD_LEFT);
                $timeline .= str_repeat($this->line, $segmentWidth - 1).'|';
                $eventTexts .= str_pad($eventText, $segmentWidth, ' ', STR_PAD_LEFT);
            } else {
                $dates .= str_pad($date, $segmentWidth, ' ', STR_PAD_BOTH);
                $timeline .= str_repeat($this->line, $segmentWidth - 1).$this->connector;
                $eventTexts .= str_pad($eventText, $segmentWidth, ' ', STR_PAD_BOTH);
            }
        }

        $this->style->writeln($dates);
        $this->style->writeln($timeline);
        $this->style->writeln($eventTexts);
    }

    /**
     * @param array<int, array{date: string, event: string}> $events
     */
    public static function create(SymfonyStyle $style, array $events = [], bool $verboseOnly = false): self
    {
        return (new self($style, $verboseOnly))->setEvents($events);
    }

    /**
     * @param array<int, array{date: string, event: string}> $events
     */
    public static function withDots(SymfonyStyle $style, array $events = []): self
    {
        return self::create($style, $events)
            ->setConnector('•')
            ->setLine('.');
    }

    /**
     * @param array<int, array{date: string, event: string}> $events
     */
    public static function withStars(SymfonyStyle $style, array $events = []): self
    {
        return self::create($style, $events)
            ->setConnector('★')
            ->setLine('─');
    }

    public function __toString(): string
    {
        return 'ToDo';
    }
}
