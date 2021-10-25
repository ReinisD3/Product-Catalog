<?php

namespace App\Services\Products;

use App\Models\Collections\TagsCollection;
use App\Models\Tag;

class EditServiceRequest
{
    private string $productId;
    private string $userId;
    private string $productName;
    private string $productAmount;
    private string $categoryId;
    private TagsCollection $tagsCollection;

    public function __construct(array $post, array $productId, string $userId)
    {
        $this->productId = $productId['id'];
        $this->userId = $userId;
        $this->productName = $post['name'];
        $this->productAmount = $post['amount'];
        $this->categoryId = $post['categoryId'];
        $this->tagsCollection = new TagsCollection(array_map(fn($t) => new Tag($t), $post['tags']));
    }
    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getProductAmount(): string
    {
        return $this->productAmount;
    }

    public function getTagsCollection(): TagsCollection
    {
        return $this->tagsCollection;
    }
    public function getCategoryId(): int
    {
        return (int)$this->categoryId;
    }
}