<?php

declare(strict_types=1);

namespace Translation\Command;

use Symfony\Component\Console\Input\InputArgument;
use Translation\Entity\TextFile;
use Translation\Services\TranslationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SortTranslationsCommand extends Command
{
    public function __construct(private TranslationService $service)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('translations:sort')
            ->addArgument('file', InputArgument::REQUIRED)
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);
        $filePath = $input->getArgument('file');

        if (!$file = TextFile::createForReading($filePath)) {
            $style->error('File is not accessible, check path');
            return Command::FAILURE;
        }

        $translations = $this->service->parseTranslation($file);
        $this->service->sort($translations);

        if (!$newFile = TextFile::createForWriting($file)) {
            $style->error('New file is not accessible, check permissions');
            return Command::FAILURE;
        }

        $this->service->saveTranslationsToFile($translations, $newFile);

        $style->success('Done');
        return Command::SUCCESS;
    }
}