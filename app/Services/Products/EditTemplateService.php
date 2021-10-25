<?php

namespace App\Services\Products;

class EditTemplateService extends BaseProductService
{
    public function execute(EditTemplateServiceRequest $request):ServiceResponse
    {
        $serviceResponse = new ServiceResponse();
        $serviceResponse->setProduct($this->productsRepository->filterOneById($request->getProductId(), $request->getUserId()));
        $serviceResponse->setUserName();
        $serviceResponse->setErrors();
        $serviceResponse->setDefinedCategories($this->definedCategories);
        $serviceResponse->setDefinedTags($this->definedTags);
        return $serviceResponse;
    }
}