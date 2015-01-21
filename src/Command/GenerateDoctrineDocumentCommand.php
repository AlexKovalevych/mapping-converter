<?php

namespace Kovalevych\MappingConverter\Command;

use Kovalevych\MappingConverter\Field\SimpleField;
use Kovalevych\MappingConverter\Field\ReferenceOneField;
use Kovalevych\MappingConverter\Field\ReferenceManyField;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateDoctrineDocumentsCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('convert:mangango-to-doctrine')
            ->setDescription('Convert mandango mappings to doctrine documents')
            ->addArgument('source', InputArgument::REQUIRED, 'Enter path to mangango mappings')
            ->addArgument('destination', InputArgument::OPTIONAL, 'Enter path where put generated documents')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $mondatorPath = __DIR__ . '/../../config/mondator/';
        $resultPath = __DIR__ . '/../../Cache/doctrine_entities/';
        if (!file_exists($resultPath)) {
            mkdir($resultPath);
        }
        if ($handle = opendir($mondatorPath)) {
            echo "Processing files:\n";

            while (false !== ($entry = readdir($handle))) {
                $filename = $mondatorPath . $entry;
                if (!is_file($filename)) {
                    continue;
                }

                $config = include $filename;
                $generateDocument = $this->generateDocument($config);
                file_put_contents($resultPath . str_replace(' ', '', ucwords(str_replace('_', ' ', $entry))), $generateDocument);
                echo "$entry\n";
            }

            closedir($handle);
        }
    }
}
