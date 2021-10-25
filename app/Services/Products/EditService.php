<?php

namespace App\Services\Products;

class EditService extends BaseProductService
{

    public function execute(EditServiceRequest $editRequest):void
    {
        $product = $this->productsRepository->filterOneById($editRequest->getProductId(), $editRequest->getUserId());

        if ($editRequest->getProductName() !== '') $product->setName($editRequest->getProductName());
        $product->setCategoryId($editRequest->getCategoryId());
        if ($editRequest->getProductAmount() !== '') $product->setAmount((int)$editRequest->getProductAmount());
        $product->setLastEditedAt();
        $product->setTagsCollection($editRequest->getTagsCollection());
        $this->productsRepository->save($product, $editRequest->getUserId());
    }
}