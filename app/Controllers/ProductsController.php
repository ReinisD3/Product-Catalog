<?php

namespace app\Controllers;

use App\Auth;
use App\Exceptions\FormValidationException;
use app\Exceptions\RepositoryValidationException;
use App\Models\Collections\ProductsCollection;
use app\Models\Collections\TagsCollection;
use App\Models\Product;
use App\Redirect;
use App\Repositories\MysqlCategoriesRepository;
use App\Repositories\MysqlProductsRepository;
use App\Repositories\MysqlTagsRepository;
use App\Repositories\ProductsRepositoryInterface;
use App\Validation\ProductsValidation;
use App\View;
use Carbon\Carbon;
use Carbon\Exceptions\Exception;

class ProductsController
{
    private ProductsRepositoryInterface $repository;
    private ProductsValidation $validator;
    private TagsCollection $definedTags;
    private array $categories;


    public function __construct()
    {
        $this->repository = new MysqlProductsRepository();
        $this->validator = new ProductsValidation();
        $this->definedTags = $this->repository->definedTagsCollection();
        $this->categories = $this->repository->getCategories();

    }

    public function index(): View
    {
        return new View('Products/index.twig');
    }

    public function show(): View
    {
        $productCollection = $this->repository->getAll();

        return new View('Products/show.twig',
            ['productCollection' => $productCollection,
                'categories' => $this->categories,
                'tags' => $this->definedTags,
                'userName' => Auth::user($_SESSION['id'])]);
    }

    public function addTemplate(): View
    {

        return new View('Products/add.twig',
            ['categories' => $this->categories,
                'userName' => Auth::user($_SESSION['id']),
                'tags' => $this->definedTags,
                'errors' => $_SESSION['errors']]);
    }

    public function add(): object
    {
        try {
            $this->validator->validateAdd($_POST);
            $tags = array_map(fn($t) => $this->definedTags->getTagById($t), $_POST['tags']);
            $tagsCollection = new TagsCollection($tags);

            $product = new Product(
                $_POST['name'],
                $_POST['categoryId'],
                $_POST['amount'],
                $_SESSION['id'] ?? null,
                $tagsCollection
            );
            $this->repository->save($product);
            return new Redirect('/products/show');
        } catch (FormValidationException | RepositoryValidationException $e) {

            $_SESSION['errors'] = $this->validator->getErrors();

            return new View('Products/add.twig',
                ['categories' => $this->categories,
                    'userName' => Auth::user($_SESSION['id']),
                    'tags' => $this->definedTags,
                    'errors' => $_SESSION['errors']]);
        }

    }

    public function editTemplate(array $product): View
    {
        $editProduct = $this->repository->getAll($product['id'])->getProducts()[0];

        return new View('Products/edit.twig',
            ['product' => $editProduct,
                'categories' => $this->repository->getCategories(),
                'tags' => $this->definedTags,
                'errors' => $_SESSION['errors']]);
    }


    public function edit(array $product): object
    {
        try {

            /** @var Product $product */
            $product = $this->repository->getAll($product['id'])->getProducts()[0];
            $tags = array_map(fn($t) => $this->definedTags->getTagById($t), $_POST['tags']);
            $tagsCollection = new TagsCollection($tags);

            if ($_POST['name'] !== '') $product->setName($_POST['name']);
            $product->setCategoryId((int)$_POST['categoryId']);
            if ($_POST['amount'] !== '') $product->setAmount((int)$_POST['amount']);
            $product->setLastEditedAt();
            $product->setTagsCollection($tagsCollection);
            $this->repository->save($product);

            return new Redirect('/products/show');
        } catch (FormValidationException $e) {
            $_SESSION['errors'] = $this->validator->getErrors();
            return new View('Products/edit.twig',
                ['product' => $product,
                    'categories' => $this->repository->getCategories(),
                    'errors' => $_SESSION['errors'],
                    'tags' => $this->definedTags]);


        }


    }

    public function filterTemplate(): View
    {
        return new View('Products/filter.twig',
            ['categories' => $this->repository->getCategories(),
                'tags' => $this->definedTags,]);
    }

    public function filter(): View
    {
        try {
            if ($_GET['categoryId'] == 'all') $_GET['categoryId'] = null;
            $categoryProductsCollection = $this->repository->getAll(null, $_GET['categoryId'] ?? null);

            if (!empty($_GET['tags'])) {
                $filterTagsCollection = new TagsCollection();
                foreach ($_GET['tags'] as $tagId) {
                    $filterTagsCollection->add($this->definedTags->getTagById($tagId));
                }
                $categoryProductsCollection->filterByTags($filterTagsCollection);
            }

            return new View('Products/show.twig',
                ['productCollection' => $categoryProductsCollection,
                    'categories' => $this->repository->getCategories(),
                    'tags' => $this->definedTags,
                    'userName' => Auth::user($_SESSION['id'])]);
        }catch(RepositoryValidationException $e)
        {
            $_SESSION['errors'] = $this->validator->getErrors();
            return  new View('Products/filter.twig',
            ['categories' => $this->repository->getCategories(),
                'tags' => $this->definedTags,]);
        }
    }

    public function delete(array $id): Redirect
    {
        $product = $this->repository->getAll($id['id'])->getProducts()[0];
        $this->repository->delete($product);
        return new Redirect('/products/show');
    }

}