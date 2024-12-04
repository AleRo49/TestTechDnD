<?php

namespace App\Tests\Command;

use App\Command\CsvReadCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CsvReadCommandTest extends KernelTestCase
{

    private CommandTester $commandTester;

    protected function setUp(): void
    {
        $application = new Application();
        $application->add(new CsvReadCommand());
        $command = $application->find('app:csv-read');
        $this->commandTester = new CommandTester($command);
    }

    /**
     * @test
     */
    public function name_is_configured_correctly(): void
    {
        $csvReadCommand = new CsvReadCommand();
        $this->assertEquals('app:csv-read', $csvReadCommand->getName());
    }

    /**
     * @test
     */
    public function description_is_configured_correctly(): void
    {
        $csvReadCommand = new CsvReadCommand();
        $this->assertEquals('Read a CSV and displays the result', $csvReadCommand->getDescription());
    }

    /**
     * @test
     */
    public function help_is_configured_correctly(): void
    {
        $csvReadCommand = new CsvReadCommand();
        $this->assertEquals(
            'Reads a CSV file passed as a parameter and displays the result as a table or json in the console.',
            $csvReadCommand->getHelp()
        );
    }

    /**
     * @test
     */
    public function argument_file_is_configured_correctly(): void
    {
        $csvReadCommand = new CsvReadCommand();
        $this->assertTrue($csvReadCommand->getDefinition()->hasArgument('file'));
        $this->assertEquals(
            'CSV file path to read, eg. public/products.csv',
            $csvReadCommand->getDefinition()->getArgument('file')->getDescription()
        );
    }

    /**
     * @test
     */
    public function option_json_is_configured_correctly(): void
    {
        $csvReadCommand = new CsvReadCommand();
        $this->assertTrue($csvReadCommand->getDefinition()->hasOption('json'));
        $this->assertEquals('Should rendering be in JSON ?', $csvReadCommand->getDefinition()->getOption('json')->getDescription());
    }

    public function testExecute(): void
    {
        $this->commandTester->execute([
            'file' => 'public/products.csv'
        ]);

        $this->commandTester->assertCommandIsSuccessful();
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Sku', $output);
    }

    public function testExecuteWrongFile(): void
    {
        $this->commandTester->execute([
            'file' => '/not/public/products.csv'
        ]);
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('File not found', $output);
    }
}
