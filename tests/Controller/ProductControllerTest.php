<?php

namespace App\Tests\Controller;

use App\Controller\ProductController;
use Exception;
use PHPUnit\Framework\TestCase;
use App\Reader\CsvReader;

class ProductControllerTest extends TestCase
{
    private string $csvPath = 'public/products.csv';

    /**
     * @throws Exception
     */
    public function testReadCsvFile()
    {
        $productController = new ProductController();
        $productController->setReader(new CsvReader($this->csvPath));

        $productController->readCsvFile($this->csvPath);
        $products = $productController->getProducts();

        $this->assertIsArray($products);
        $this->assertCount(2, $products);
    }

    /**
     * @throws Exception
     */
    public function testGetTableOutput()
    {
        $productController = new ProductController();
        $productController->setReader(new CsvReader($this->csvPath));
        $productController->readCsvFile($this->csvPath);

        $tableOutput = $productController->getTableOutput();

        $this->assertIsArray($tableOutput);
        $this->assertArrayHasKey('header', $tableOutput);
        $this->assertArrayHasKey('body', $tableOutput);
    }

    /**
     * @throws Exception
     */
    public function testGetJsonOutput()
    {
        $productController = new ProductController();
        $productController->setReader(new CsvReader($this->csvPath));
        $productController->readCsvFile($this->csvPath);

        $jsonOutput = $productController->getJsonOutput();

        $this->assertIsString($jsonOutput);

        $decodedOutput = json_decode($jsonOutput, true);

        $this->assertIsArray($decodedOutput);
        $this->assertCount(2, $decodedOutput);
    }
}
