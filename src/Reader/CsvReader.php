<?php

namespace App\Reader;

use Exception;

class CsvReader
{
    /**
     * @var string $csv CSV file path
     */
    private string $csv;
    /**
     * @var array $header CSV Header line
     */
    private array $header = [];
    /**
     * @var array $lines CSV body lines
     */
    private array $lines = [];

    /**
     * @param string $csvPath
     */
    public function __construct(string $csvPath)
    {
        $this->csv = $csvPath;
    }

    /**
     * Read the file
     * @throws Exception
     */
    public function read(): void
    {
        if (!$handle = fopen($this->csv, 'r')){
            throw new Exception('Cannot open csv file at '.$this->csv);
        }

        $i = 0;
        while (($row = fgetcsv($handle, null, ";")) !== false) {
            // First line as header
            if ($i === 0) {
                $this->header = $row;
            } else {
                $line = [];
                foreach ($this->header as $hKey => $hValue) {
                    $line[$hValue] = $row[$hKey];
                }
                $this->lines[] = $line;
            }
            $i++;
        }
        fclose($handle);
    }

    /**
     * @return array
     */
    public function getHeader(): array
    {
        return $this->header;
    }

    /**
     * @return array
     */
    public function getLines(): array
    {
        return $this->lines;
    }
}