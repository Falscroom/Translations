<?php

declare(strict_types=1);

namespace Translation\Entity;

use Generator;

class TextFile
{
    private const NEW_FILE_POSTFIX = '-sorted.properties';

    private function __construct(
        private string $path,
        private $handle
    ) {
    }

    public static function createForReading(string $path): ?self
    {
        if ($handle = fopen($path, 'r')) {
            return new self($path, $handle);
        }

        return null;
    }

    public static function createForWriting(self $from): ?self
    {
        $newPath = substr($from->path, 0, strpos($from->path, '.')) . self::NEW_FILE_POSTFIX;

        if ($handle = fopen($newPath, 'w')) {
            return new self($newPath, $handle);
        }

        return null;
    }

    public function getLines(): iterable
    {
        while (($line = fgets($this->handle)) !== false) {
            yield $line;
        }
    }

    public function writeLine(string $text): bool
    {
        return fwrite($this->handle, $text) !== false;
    }
}