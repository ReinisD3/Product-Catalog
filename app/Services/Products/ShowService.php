<?php

namespace App\Services\Products;


class ShowService extends BaseProductService
{
      public function execute(ShowServiceRequest $showRequest) : ServiceResponse
    {
        $userId = $showRequest->getUserId();
        $serviceResponse = new ServiceResponse();
        $serviceResponse->setProductCollection($this->productsRepository->getAll($userId));
        $serviceResponse->setUserName();
        $serviceResponse->setDefinedCategories($this->definedCategories);
        $serviceResponse->setDefinedTags($this->definedTags);

        return $serviceResponse;
    }
}