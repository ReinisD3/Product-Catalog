<?php

namespace app\Validation\Products;

use App\Exceptions\FormValidationException;
use App\Middleware\MiddlewareInterface;
use App\Repositories\MysqlCategoriesRepository;
use App\Repositories\MysqlTagsRepository;
use App\Validation\BaseValidation;
use App\Redirect;


class AddFormValidation extends BaseValidation implements MiddlewareInterface
{

    public function handle(?array $data = null): void
    {
        $categoriesRepository = new MysqlCategoriesRepository();
        $tagsRepository = new MysqlTagsRepository();

        try {
            if ($_POST['name'] == '') {
                $this->errors->add('name', 'Invalid name');
            }
            if (!is_numeric($_POST['amount'])) {
                $this->errors->add('amount', 'Needs to be numeric value');
            }
            if ($categoriesRepository->getNameById($_POST['categoryId']) == null)
                $this->errors->add('categoryId', 'Wrong category input');
            if (isset($_POST['tags']) && !$tagsRepository->tagsIsDefined($_POST['tags']))
                $this->errors->add('tags', 'Wrong Tags input');

            $this->checkErrors();

        } catch (FormValidationException $e) {
            $_SESSION['errors'] = $this->getErrors();
            Redirect::url('/products/add');
        }


    }
}