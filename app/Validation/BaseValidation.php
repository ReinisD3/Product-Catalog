<?php

namespace App\Validation;

use App\Errors;
use App\Exceptions\FormValidationException;
use App\Repositories\CategoriesRepositoryInterface;
use App\Repositories\MysqlCategoriesRepository;
use App\Repositories\MysqlProductsRepository;
use App\Repositories\MysqlTagsRepository;
use App\Repositories\MysqlUsersRepository;
use App\Repositories\ProductsRepositoryInterface;
use App\Repositories\TagsRepositoryInterface;
use App\Repositories\UsersRepositoryInterface;

abstract class BaseValidation
{
    protected Errors $errors;
    protected ProductsRepositoryInterface $productsRepository;
    protected CategoriesRepositoryInterface $categoriesRepository;
    protected TagsRepositoryInterface $tagsRepository;
    protected UsersRepositoryInterface $usersRepository;


    public function __construct(MysqlProductsRepository $productsRepository,
                                MysqlCategoriesRepository $categoriesRepository,
                                MysqlTagsRepository $tagsRepository,
                                MysqlUsersRepository $usersRepository)
    {
        $this->errors = new Errors();
        $this->categoriesRepository = $categoriesRepository;
        $this->productsRepository = $productsRepository;
        $this->tagsRepository = $tagsRepository;
        $this->usersRepository = $usersRepository;
    }

    public function getErrors(): array
    {
        return $this->errors->all();
    }

    protected function checkErrors(): void
    {
        if (count($this->errors->all()) > 0) {
            throw new FormValidationException();
        }
    }
}