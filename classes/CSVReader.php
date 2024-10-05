<?php

class CSVReader
{
    private array $rawData;
    private array $header = [];
    private string $headerSeparator = ";";
    private array $data = [];
    private string $dataSeparator = ",";


    public function getHeader(): array
    {
        return $this->header;
    }
    public function getData(): array
    {
        return $this->data;
    }

    public function getHeaderSeparator(): string
    {
        return $this->headerSeparator;
    }
    public function getDataSeparator(): string
    {
        return $this->dataSeparator;
    }

    public function __construct(string $filePath, ?string $separator = null)
    {
        if (!file_exists($filePath)) {
            throw new Exception("Arquivo csv não existe");
        }
        $this->rawData = file($filePath);
        if (!$this->rawData || count($this->rawData) === 0) {
            throw new Exception("Arquivo csv está vazio");
        }

        if (count($this->rawData) === 1) {
            throw new Exception("Arquivo csv está sem dados, apenas cabeçalhos");
        }

        $this->parse($separator);
    }

    private function parse(?string $separator = null)
    {
        if ($separator !== null) {
            $this->headerSeparator = $this->dataSeparator = $separator;
            $this->constructHeader();
            $this->constructData();
            if (!$this->isValid()) {
                throw new Exception("O CSV é inválido, quantidade de colunas do cabeçalho difere das demais linhas. Verifique o separador selecionado");
            }
            return;
        }

        $commaHeader = $this->constructHeader();
        $commaData = $this->constructData();

        if (count($commaHeader) > 1 && count($commaData[0]) === 1) {
            $this->dataSeparator = ";";
            $this->constructData();
            if (!$this->isValid()) {
                throw new Exception("O CSV é inválido, quantidade de colunas do cabeçalho difere das demais linhas. Selecione um separador somente para cabeçalho e outras linhas");
            }
            return;
        }

        if (count($commaHeader) === 1 && count($commaData[0]) > 1) {
            $this->headerSeparator = ";";
            $this->constructHeader();
            if (!$this->isValid()) {
                throw new Exception("O CSV é inválido, quantidade de colunas do cabeçalho difere das demais linhas. Selecione um separador somente para cabeçalho e outras linhas");
            }
            return;
        }

        if(count($commaHeader) === 1 && count($commaData[0]) === 1) {
            $this->headerSeparator = $this->dataSeparator = ";";
            $this->constructHeader();
            $this->constructData();
            if (!$this->isValid()) {
                $this->header = $commaHeader;
                $this->data = $commaData;
            }
            return;
        }
    }
    private function isValid()
    {
        return count($this->header) === count($this->data[0]);
    }

    private function constructHeader(): array
    {
        $this->header = str_getcsv(mb_convert_encoding($this->rawData[0], 'UTF-8', 'ISO-8859-1'), $this->headerSeparator);
        return $this->header;
    }
    private function constructData(): array
    {
        $this->data = [];
        foreach ($this->rawData as $index => $line) {
            if ($index === 0) {
                continue;
            }
            $this->data[] = str_getcsv(mb_convert_encoding($line, 'UTF-8', 'ISO-8859-1'), $this->dataSeparator);
        }
        return $this->data;
    }

    public function __toString(): string
    {
        $out = implode(',', $this->header) . PHP_EOL;
        foreach ($this->data as $line) {
            $out = $out . implode(',', $line) . PHP_EOL;
        }
        return $out;
    }
}