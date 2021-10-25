<?php

namespace App\Services\Products;

use App\Models\Collections\TagsCollection;
use App\Models\Product;
use App\Models\Tag;

class AddServiceRequest
{
    private Product $product;

    public function __construct(array $post, string $userId)
    {
        $this->post = $post;
        $this->userId = $userId;
        $tags = array_map(fn($t) => new Tag($t), $post['tags']);
        $tagsCollection = new TagsCollection($tags);

        $this->product = new Product(
            $post['name'],
            $post['categoryId'],
            $post['amount'],
            $userId,
            $tagsCollection
        );
    }

    public function getProduct(): Product
    {
        return $this->product;
    }
}