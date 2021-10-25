<?php

namespace App\Services\Products;

use App\Models\Collections\TagsCollection;
use App\Repositories\MysqlCategoriesRepository;
use App\Repositories\MysqlProductsRepository;
use App\Repositories\MysqlTagsRepository;

abstract class BaseProductService
{
    protected MysqlProductsRepository $productsRepository;
    protected MysqlCategoriesRepository $categoriesRepository;
    protected MysqlTagsRepository $tagsRepository;
    protected TagsCollection $definedTags ;
    protected array $definedCategories ;

    public function __construct(MysqlProductsRepository   $productsRepository,
                                MysqlCategoriesRepository $categoriesRepository,
                                MysqlTagsRepository       $tagsRepository)
    {
        $this->productsRepository = $productsRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->tagsRepository = $tagsRepository;
        $this->definedTags = $this->tagsRepository->getAll();
        $this->definedCategories = $this->categoriesRepository->getAll();
    }
}