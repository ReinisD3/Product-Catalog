<?php

namespace App\Repositories;

use App\Models\Collections\ProductsCollection;
use App\Models\Collections\TagsCollection;
use App\Models\Product;
use App\Services\Products\FilterServiceRequest;

interface ProductsRepositoryInterface
{
    public function getAll(?string $userId = null):?ProductsCollection;
    public function save(Product $product, string $userId):void;
    public function delete(Product $product, string $userId):void;
    public function filter(FilterServiceRequest $filterRequest): ?ProductsCollection;
    public function filterOneById(string $id, string $user): ?Product;
    public function getProductTagsCollection(string $productId): TagsCollection;
    public function makeProductCollectionFromMySQLFetch_Class(?array $products = null): ?ProductsCollection;
    public function executeQueryFetchClass(string $sql):array;
}