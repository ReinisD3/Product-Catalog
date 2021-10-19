<?php

namespace app\Validation;

use App\Errors;
use App\Exceptions\FormValidationException;

class ProductsValidation
{
    private Errors $errors;

    public function __construct()
    {
        $this->errors = new Errors();
;    }
    public function getErrors(): Errors
    {
        return $this->errors;
    }
    public function validateAdd(array $data):void
    {
        if ($_POST['name'] == '') {
            $this->errors->add('productName','Invalid name');
        }
        if (!is_numeric($_POST['amount'])) {
            $this->errors->add('productAmount','Need to be numeric value');
        }

        if(count($this->errors->all()) > 0) {
            throw new FormValidationException();
        }

    }

}