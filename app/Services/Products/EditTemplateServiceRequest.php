<?php

namespace App\Services\Products;

class EditTemplateServiceRequest
{
    private string $productId;
    private string $userId;

    public function __construct(string $productId, string $userId)
    {

        $this->productId = $productId;
        $this->userId = $userId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}