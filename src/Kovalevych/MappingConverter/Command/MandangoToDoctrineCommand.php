<?php

namespace Kovalevych\MappingConverter\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Kovalevych\MappingConverter\Converter\MandangoToDoctrineConverter;

class MandangoToDoctrineCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('convert:mandango-to-doctrine')
            ->setDescription('Convert mandango mappings to doctrine documents')
            ->addArgument('source', InputArgument::REQUIRED, 'Enter path to mangango mappings')
            ->addArgument('destination', InputArgument::OPTIONAL, 'Enter path where put generated documents')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $sourcePath = $input->getArgument('source');
        $destinationPath = $input->getArgument('destination');
        $finder = new Finder();
        $fs = new Filesystem();
        $converter = new MandangoToDoctrineConverter();

        try {
            if (!$fs->exists($destinationPath)) {
                $fs->mkdir($destinationPath);
            }
        } catch (IOExceptionInterface $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }

        $finder->files()->in($sourcePath);
        $progress = new ProgressBar($output, $finder->count());
        $progress->setMessage(sprintf('Processing files in "%s"', $sourcePath));
        foreach ($finder as $fileInfo) {
            $progress->setMessage(sprintf('Processing "%s"', $fileInfo->getRealPath()));
            $generatedDocument = $this->processMandangoConfigFile($converter, $fileInfo->getRealPath());
            $destinationFilename = sprintf('%s/%s', $destinationPath, str_replace(' ', '', ucwords(str_replace('_', ' ', $fileInfo->getFilename()))));
            $fs->dumpFile($destinationFilename, $generatedDocument);
            $progress->advance();

        }
        $progress->setMessage('Done');
        $progress->finish();
    }

    protected function processMandangoConfigFile($converter, $filename)
    {
        $config = include $filename;

        return $converter->generateDocument($config);
    }
}
