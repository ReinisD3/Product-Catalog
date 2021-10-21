<?php

namespace app\Validation;

use App\Errors;
use App\Exceptions\FormValidationException;
use App\Repositories\MysqlCategoriesRepository;

abstract class BaseValidation
{
    protected Errors $errors;
    protected MysqlCategoriesRepository $categoriesRepository;

    public function __construct()
    {
        $this->errors = new Errors();
        $this->categoriesRepository = new MysqlCategoriesRepository();
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