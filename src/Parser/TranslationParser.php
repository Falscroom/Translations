<?php

declare(strict_types=1);

namespace Translation\Parser;


use Translation\Entity\Translation;
use Translation\Factory\TranslationFactory;

class TranslationParser
{
    private const COMMENT_PREFIX = '#';
    private const PATTERN = '/(?<key>.*?) ?= ?(?<value>.*)/i';

    public static function isComment(string $line): bool
    {
        return mb_substr(trim($line), 0, 1) === self::COMMENT_PREFIX;
    }

    public function parseTranslation(string $line): ?Translation
    {
        $matches = [];

        if (preg_match(self::PATTERN, $line, $matches)) {
            ['key' => $key, 'value' => $value] = $matches;

            return TranslationFactory::newTranslation($key, $value);
        }

        return null;
    }
}