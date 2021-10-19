<?php

namespace app;

use App\Auth;

class View
{
    private string $fileName;
    private ?array $data;

    public function __construct(string $fileName, ?array $data = null)
    {
        $this->fileName = $fileName;
        $this->data['userName'] = Auth::user($_SESSION['id']);
        foreach ($data as $key => $datum) {
            $this->data[$key] = $datum;
        }

    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getData(): ?array
    {
        return $this->data ?? null;
    }

}