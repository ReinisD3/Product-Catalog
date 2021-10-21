<?php

namespace app\Repositories;

use App\Models\Collections\ProductsCollection;
use App\Models\Product;

interface ProductsRepositoryInterface
{
    public function getAll(?string $userId = null):ProductsCollection;
    public function save(Product $product, string $userId):void;
    public function delete(Product $product, string $userId):void;
}