<?php

namespace App\Repositories;

use App\Models\Collections\TagsCollection;
use App\Models\Product;

interface TagsRepositoryInterface
{
    public function getAll(): TagsCollection;
    public function save(Product $product): void;
    public function getNameById(string $id): ?string;
    public function tagsIsDefined(array $tags):bool;
}