<?php

declare(strict_types=1);

namespace Translation\Parser;


use Translation\Entity\Translation;
use Translation\Factory\TranslationFactory;

class TranslationParser
{
    private const COMMENT_PREFIX = '#';
    private const SEPARATOR_SYMBOL = '=';

    public static function isComment(string $line): bool
    {
        return mb_substr(trim($line), 0, 1) === self::COMMENT_PREFIX;
    }

    public function parseTranslation(string $line): ?Translation
    {
        $matches = explode(self::SEPARATOR_SYMBOL, $line);

        if (count($matches) === 2) {
            [$key, $value] = $matches;

            return TranslationFactory::newTranslation($key, $value);
        }

        return null;
    }
}