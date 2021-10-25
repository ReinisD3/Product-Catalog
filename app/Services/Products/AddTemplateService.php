<?php

namespace App\Services\Products;

class AddTemplateService extends BaseProductService
{
    public function execute():ServiceResponse
    {
        $serviceResponse = new ServiceResponse();
        $serviceResponse->setDefinedTags($this->definedTags);
        $serviceResponse->setDefinedCategories($this->definedCategories);
        $serviceResponse->setUserName();
        $serviceResponse->setErrors();
        return $serviceResponse;
    }
}