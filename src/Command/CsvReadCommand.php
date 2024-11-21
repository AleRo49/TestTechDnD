<?php

// src/Command/CsvReadCommand.php
namespace App\Command;

use App\Controllers\ProductController;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:csv-read')]
class CsvReadCommand extends Command
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('app:csc-read')
            ->setDescription('Read a CSV and displays the result')
            ->setHelp('Reads a CSV file passed as a parameter and displays the result as a table or json in the console.');

        $this->addArgument('file', InputArgument::REQUIRED, 'CSV file path to read, eg. public/products.csv');
        $this->addOption('json', null, InputOption::VALUE_NONE, 'Should rendering be in JSON ?');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $filePath = $input->getArgument('file');
        if (!file_exists($filePath)) {
            $io->error("File not found at: $filePath");
            return Command::FAILURE;
        }

        $jsonOutput = $input->getOption('json');

        try {
            $controller = new ProductController();
            $controller->readCsvFile($filePath);

            if ($jsonOutput) {
                $result = $controller->getJsonOutput();
                $io->text($result);
            } else {
                $result = $controller->getTableOutput();
                $io->table($result['header'], $result['body']);
            }
        } catch (Exception $e){
            $io->error("An exception occurred : ".$e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}