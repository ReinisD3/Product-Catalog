<?php

namespace App\Services\Products;

class FilterService extends BaseProductService
{
    public function execute(FilterServiceRequest $filterRequest):ServiceResponse
    {
        $filterResponse = new ServiceResponse();
        $filterResponse->setUserName();
        $filterResponse->setDefinedCategories($this->definedCategories);
        $filterResponse->setDefinedTags($this->definedTags);
        $filterResponse->setProductCollection($this->productsRepository->filter($filterRequest)??null);
        return $filterResponse;
    }

}