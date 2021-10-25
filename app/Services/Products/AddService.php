<?php

namespace App\Services\Products;



class AddService extends BaseProductService
{

    public function execute(AddServiceRequest $addRequest):void
    {
        $product = $addRequest->getProduct();
        $this->productsRepository->save($product,$product->getUser());
    }
}