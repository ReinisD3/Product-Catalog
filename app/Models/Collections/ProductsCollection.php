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

}