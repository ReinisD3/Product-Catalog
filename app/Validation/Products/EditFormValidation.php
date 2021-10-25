<?php

namespace App\Validation\Products;

use App\Exceptions\FormValidationException;
use App\Middleware\MiddlewareInterface;
use App\Validation\BaseValidation;
use App\Redirect;

class EditFormValidation extends BaseValidation implements MiddlewareInterface
{

    public function handle(?array $data = null): void
    {

        try {
            if ($_POST['name'] == '') {
                $this->errors->add('name', 'Invalid name');
            }
            if (!is_numeric($_POST['amount'])) {
                $this->errors->add('amount', 'Needs to be numeric value');
            }
            if ($this->categoriesRepository->getNameById($_POST['categoryId']) == null)
                $this->errors->add('categoryId', 'Wrong category input');
            if (isset($_POST['tags']) && !$this->tagsRepository->tagsIsDefined($_POST['tags']))
                $this->errors->add('tags', 'Wrong Tags input');
            $this->checkErrors();
        } catch (FormValidationException $e) {
            $_SESSION['errors'] = $this->getErrors();
            Redirect::url("/products/edit/{$data['id']}");

        }
    }
}