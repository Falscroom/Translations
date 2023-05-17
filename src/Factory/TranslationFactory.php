<?php

declare(strict_types=1);

namespace Translation\Factory;

use Translation\Entity\Translation;

class TranslationFactory
{
    public static function newTranslation(string $key, string $value): Translation
    {
        return new Translation($key, $value);
    }
}