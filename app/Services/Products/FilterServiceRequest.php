<?php

namespace App\Services\Products;

use App\Models\Collections\TagsCollection;
use App\Models\Tag;

class FilterServiceRequest
{
    private string $categoryId;
    private TagsCollection $tagsCollection;
    private string $userId;

    public function __construct(array $get, string $userId)
    {
        $this->categoryId = $get['categoryId'];
        $this->tagsCollection = new TagsCollection(array_map(fn($t) => new Tag($t), $get['tags']));
        $this->userId = $userId;
    }
    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    public function getTagsCollection(): TagsCollection
    {
        return $this->tagsCollection;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }


}