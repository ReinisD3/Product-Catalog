<?php

namespace App\Services\Products;


class DeleteService extends BaseProductService
{
    public function execute(DeleteServiceRequest $deleteRequest)
    {
        $product = $this->productsRepository->filterOneById($deleteRequest->getProductId(), $deleteRequest->getUserId());
        $this->productsRepository->delete($product, $deleteRequest->getUserId());
    }
}