<?php

namespace app\Models\Collections;

use App\Models\Product;
use App\Models\Collections\TagsCollection;

class ProductsCollection
{
    private array $products;

    public function __construct(array $products = [])
    {
        foreach ($products as $product) {
            if ($product instanceof Product) {
                $this->add($product);
            }
        }
    }

    public function add(Product $product): void
    {
        $this->products[] = $product;
    }

    public function getProducts(): array
    {
        return $this->products;
    }
    public function filterByTags(?TagsCollection $tagsCollection = null) :void
    {
        $filteredProducts = [];

        /** @var Product $product */
        foreach ($this->products as $product)
        {
            $tagsMatched = array_intersect_key($product->getTagsCollection()->getTags(),$tagsCollection->getTags());
           if(!empty($tagsMatched) && count($tagsCollection->getTags()) == count($tagsMatched))
           {
               $filteredProducts [] = $product;
           }

            $this->products = $filteredProducts;
        }



    }

}