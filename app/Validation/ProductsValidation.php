<?php

namespace app\Validation;

use App\Errors;
use App\Exceptions\FormValidationException;

class ProductsValidation
{
    private Errors $errors;

    public function __construct()
    {
        $this->errors = new Errors();;
    }

    public function getErrors(): Errors
    {
        return $this->errors;
    }

    public function validateAdd(array $data): void
    {
        if ($data['name'] == '') {
            $this->errors->add('productName', 'Invalid name');
        }
        if (!is_numeric($data['amount'])) {
            $this->errors->add('productAmount', 'Needs to be numeric value');
        }

        if (count($this->errors->all()) > 0) {
            throw new FormValidationException();
        }

    }


}