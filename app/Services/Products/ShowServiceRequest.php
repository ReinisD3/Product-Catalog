<?php

namespace App\Services\Products;

class ShowServiceRequest
{

    private string $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }
    public function getUserId():string
    {
        return $this->userId;
    }
}