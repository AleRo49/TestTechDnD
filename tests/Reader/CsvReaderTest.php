<?php

namespace App\Tests\Reader;

use App\Reader\CsvReader;
use PHPUnit\Framework\TestCase;
use Exception;

class CsvReaderTest extends TestCase
{
    private string $csvPath = 'public/products.csv';

    /**
     * @throws Exception
     */
    public function testReadSuccess(): void
    {
        $csvReader = new CsvReader($this->csvPath);
        $csvReader->read();

        $this->assertIsArray($csvReader->getLines());
    }

    public function testReadFailure(): void
    {
        $this->expectException(Exception::class);
        $csvReader = new CsvReader('incorrect/path/csv.csv');

        $this->expectException(Exception::class);
        @$csvReader->read();
    }

    /**
     * @throws Exception
     */
    public function testGetHeader(): void
    {
        $csvReader = new CsvReader($this->csvPath);
        $csvReader->read();

        $this->assertEquals([
            'sku',
            'title',
            'is_enabled',
            'price',
            'currency',
            'description',
            'created_at',
        ], $csvReader->getHeader());
    }

    /**
     * @throws Exception
     */
    public function testGetLines(): void
    {
        $csvReader = new CsvReader($this->csvPath);
        $csvReader->read();

        $expectedLines = [
            [
                'sku' => '628937273',
                'title' => 'Cornelia, the dark unicorn',
                'is_enabled' => '1',
                'price' => '14.87',
                'currency' => '€',
                'description' => 'Cornelia, \\rThe Dark Unicorn.',
                'created_at' => '2018-12-12 10:34:39',
            ],
            [
                'sku' => '722821313',
                'title' => 'Be my bestie',
                'is_enabled' => '0',
                'price' => '18.80',
                'currency' => '€',
                'description' => 'Be <br/>my bestie, darling sweet.',
                'created_at' => '2018-12-12 10:34:39',
            ]
        ];

        $this->assertEquals($expectedLines, $csvReader->getLines());
    }
}
