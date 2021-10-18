<?php

namespace app\Controllers;

use App\Exceptions\FormValidationException;
use App\Models\Collections\ProductsCollection;
use App\Models\Product;
use App\Redirect;
use App\Repositories\MysqlProductsRepository;
use App\Repositories\ProductsRepositoryInterface;
use App\Validation\ProductsValidation;
use App\View;
use Carbon\Carbon;
use Carbon\Exceptions\Exception;

class ProductsController
{
    private ProductsRepositoryInterface $repository;
    private ProductsValidation $validator;


    public function __construct()
    {
        $this->repository = new MysqlProductsRepository();
        $this->validator = new ProductsValidation();

    }

    public function index(): View
    {
        return new View('Products/index.twig');
    }

    public function show(): View
    {
        $productCollection = $this->repository->getAll();
        return new View('Products/show.twig', $productCollection, 'productCollection');
    }

    public function addTemplate(): View
    {
        return new View('Products/add.twig');
    }

    public function add(): Redirect
    {
        try{
            $this->validator->validateAdd($_POST);
            $product = new Product(
                $_POST['name'],
                $_POST['category'],
                $_POST['amount']
            );
            $this->repository->save($product);
            return new Redirect('/products/show');
        }
        catch (FormValidationException $e)
        {
            $_SESSION['errors'] = $this->validator->getErrors();
            return new Redirect('/products/add');
        }



    }

    public function editTemplate(array $product): View
    {
        $editProduct = $this->repository->getAll($product['id'])->getProducts()[0];

        return new View('Products/edit.twig', $editProduct, 'product');
    }

    public function edit(array $product): Redirect
    {
        /** @var Product $product */
        $product = $this->repository->getAll($product['id'])->getProducts()[0];
        $product->setName($_POST['name']);
        $product->setCategory($_POST['category']);
        $product->setAmount($_POST['amount']);
        $product->setLastEditedAt();
        $this->repository->save($product);

        return new Redirect('/products/show');
    }

    public function filterTemplate(): View
    {
        return new View('Products/filter.twig');
    }

    public function filter(): View
    {
        $categoryProductsCollection = $this->repository->getAll(null, $_GET['category']);
        return new View('Products/filter.twig', $categoryProductsCollection, 'productCollection');
    }

    public function delete(array $id): Redirect
    {
        $product = $this->repository->getAll($id['id'])->getProducts()[0];
        $this->repository->delete($product);
        return new Redirect('/products/show');
    }

}