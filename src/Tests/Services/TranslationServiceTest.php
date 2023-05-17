<?php

declare(strict_types=1);

namespace Translation\Tests\Services;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Translation\Entity\TextFile;
use Translation\Entity\Translation;
use Translation\Factory\TranslationFactory;
use Translation\Parser\TranslationParser;
use Translation\Services\TranslationService;

class TranslationServiceTest extends TestCase
{
    private MockObject|TextFile $file;
    private TranslationService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->file = $this->createMock(TextFile::class);
        $this->service = new TranslationService(new TranslationParser());
    }

    public function testGetTranslations(): void
    {
        $translation = $this->newTranslations('key.part', 'value');
        $translation->addComments(['# comment1', '# comment2']);

        $translationLines = [
            '# comment1',
            '# comment2',
            'key.part=value'
        ];

        $this->file->expects($this->any())
            ->method('getLines')
            ->willReturnOnConsecutiveCalls($translationLines)
        ;

        $translations = $this->service->parseTranslation($this->file);
        $this->assertEquals($translation, current($translations));
    }

    public function testSaveTranslationsToFile(): void
    {
        $translation = $this->newTranslations('key.part', 'value');
        $translation->addComments(['# comment1', '# comment2']);

        $this->file->expects($this->once())
            ->method('writeLine')
            ->with((string) $translation)
            ->willReturn(true)
        ;

        $this->service->saveTranslationsToFile([$translation], $this->file);
    }

    public function testSort(): void
    {
        $translations = [
            $this->newTranslations('key2', 'value2'),
            $this->newTranslations('key1', 'value1')
        ];

        $this->service->sort($translations);

        $expectedTranslations = [
            $this->newTranslations('key1', 'value1'),
            $this->newTranslations('key2', 'value2')
        ];

        $this->assertEquals($expectedTranslations, $translations);
    }

    private function newTranslations(string $key, string $value): Translation
    {
        return TranslationFactory::newTranslation($key, $value);
    }
}