<?php

declare(strict_types=1);

namespace Translation\Services;

use RuntimeException;
use Translation\Entity\TextFile;
use Translation\Entity\Translation;
use Translation\Parser\TranslationParser;

class TranslationService
{
    public function __construct(
        private TranslationParser $parser
    ) {
    }
    
    /** @return Translation[] */
    public function parseTranslation(TextFile $file): array
    {
        $comments = [];
        $translations = [];

        foreach ($file->getLines() as $line) {
            if (TranslationParser::isComment($line)) {
                $comments[] = $line;
                continue;
            }

            if (!$translation = $this->parser->parseTranslation($line)) {
                continue;
            }

            $translations[] = $translation->addComments($comments);
            $comments = [];
        }

        return $translations;
    }

    /**
     * @param Translation[] $translations
     */
    public function saveTranslationsToFile(array $translations, TextFile $file): void
    {
        foreach ($translations as $translation) {
            if (!$file->writeLine((string) $translation)) {
                throw new RuntimeException('Error during file write');
            }
        }
    }

    public function sort(array &$translations): void
    {
        usort($translations, fn(Translation $a, Translation $b) => Translation::compare($a, $b));
    }
}