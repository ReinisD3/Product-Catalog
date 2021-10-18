<?php

namespace app;

class View
{
    private string $fileName;
    private $data;
    private ?string $dataName;

    public function __construct(string $fileName, $data = null, string $dataName = null)
    {
        $this->fileName = $fileName;
        $this->data = $data;
        $this->dataName = $dataName;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getDataName(): ?string
    {
        return $this->dataName;
    }
}