<?php

namespace app\Repositories;

use App\Models\Collections\ProductsCollection;
use App\Models\Product;

interface ProductsRepositoryInterface
{
    public function getAll(?string $id = null, ?string $category = null):?ProductsCollection;
    public function save(Product $product):void;
    public function delete(Product $product):void;
}