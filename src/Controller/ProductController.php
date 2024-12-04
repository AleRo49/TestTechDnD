<?php

namespace App\Controller;

use App\Entity\Product;
use App\Reader\CsvReader;
use Exception;

class ProductController
{
    /**
     * @var CsvReader
     */
    private CsvReader $reader;

    /** @var array<Product>  */
    private array $products;

    /**
     * @param CsvReader $reader
     * @return void
     */
    public function setReader(CsvReader $reader): void
    {
        $this->reader = $reader;
    }

    /**
     * @return Product[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }


    /**
     * Read csv file from path
     * @param string $filePath
     * @return void
     * @throws Exception
     */
    public function readCsvFile(string $filePath): void
    {
        $this->reader = new CsvReader($filePath);
        $this->reader->read();

        foreach ($this->reader->getLines() as $line) {
            $this->products[] = Product::fromArray($line);
        }
    }

    /**
     * Get formatted output for table rendering
     * @return array{header: array, body: array}
     */
    public function getTableOutput(): array
    {
        $formattedHeader = [];
        foreach ($this->reader->getHeader() as $header) {
            $formattedHeader[] = ucwords(str_replace('_', ' ', $header));
        }
        // Add slug column
        $formattedHeader[] = 'Slug';

        $formattedBody = [];
        foreach ($this->products as $product) {
            $formattedBody[] = $product->getFormattedArray();
        }

        return [
            'header' => $formattedHeader,
            'body' => $formattedBody
        ];
    }

    /**
     * Get formatted output for json rendering
     * @return string
     */
    public function getJsonOutput(): string
    {
        return json_encode($this->products);
    }
}