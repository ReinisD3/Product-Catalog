<?php

namespace App\Services\Products;

use App\Auth;
use App\Models\Collections\ProductsCollection;
use App\Models\Collections\TagsCollection;
use App\Models\Product;


class ServiceResponse
{
    private array $data = [];

    public function setProductCollection(?ProductsCollection $productCollection = null):void
    {
        $this->data['productCollection'] = $productCollection;
    }
    public function setProduct(Product $product):void
    {
        $this->data['product'] = $product;
    }
    public function setUserName():void
    {
        $this->data['userName'] = Auth::user($_SESSION['id']);
    }
    public function setDefinedCategories(array $definedCategories):void
    {
        $this->data['categories'] = $definedCategories;
    }
    public function setDefinedTags(TagsCollection $tagsCollection):void
    {
        $this->data['tags'] = $tagsCollection;
    }
    public function setErrors():void
    {
        $this->data['errors'] = $_SESSION['errors'];
    }

    public function getAll(): array
    {
        return $this->data;
    }

}