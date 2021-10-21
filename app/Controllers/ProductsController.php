<?php

namespace app\Controllers;

use App\Auth;
use App\Models\Collections\ProductsCollection;
use App\Models\Collections\TagsCollection;
use App\Models\Product;
use App\Redirect;
use App\Repositories\MysqlCategoriesRepository;
use App\Repositories\MysqlProductsRepository;
use App\Repositories\MysqlTagsRepository;
use App\Repositories\ProductsRepositoryInterface;
use App\View;


class ProductsController
{
    private ProductsRepositoryInterface $repository;
    private MysqlTagsRepository $tagsRepository;
    private MysqlCategoriesRepository $categoriesRepository;
    private TagsCollection $definedTags;
    private array $categories;

    public function __construct()
    {
        $this->repository = new MysqlProductsRepository();
        $this->tagsRepository = new MysqlTagsRepository();
        $this->categoriesRepository = new MysqlCategoriesRepository();
        $this->definedTags = $this->tagsRepository->getAll();
        $this->categories = $this->categoriesRepository->getAll();

    }

    public function index(): View
    {
        return new View('Products/index.twig');
    }

    public function show(): View
    {
        $productCollection = $this->repository->getAll($_SESSION['id']);

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

        $tags = array_map(fn($t) => $this->definedTags->getTagById($t), $_POST['tags']);
        $tagsCollection = new TagsCollection($tags);

        $product = new Product(
            $_POST['name'],
            $_POST['categoryId'],
            $_POST['amount'],
            $_SESSION['id'] ?? null,
            $tagsCollection
        );
        $this->repository->save($product,$_SESSION['id']);

        return new Redirect('/products/show');


    }

    public function editTemplate(array $productId): View
    {
        $editProduct = $this->repository->filterOneById($productId['id'],$_SESSION['id']);

        return new View('Products/edit.twig',
            ['product' => $editProduct,
                'categories' => $this->categories,
                'tags' => $this->definedTags,
                'errors' => $_SESSION['errors']]);
    }


    public function edit(array $productId): Redirect
    {

        /** @var Product $product */

        $product = $this->repository->filterOneById($productId['id'],$_SESSION['id']);
        $tags = array_map(fn($t) => $this->definedTags->getTagById($t), $_POST['tags']);
        $tagsCollection = new TagsCollection($tags);

        if ($_POST['name'] !== '') $product->setName($_POST['name']);
        $product->setCategoryId((int)$_POST['categoryId']);
        if ($_POST['amount'] !== '') $product->setAmount((int)$_POST['amount']);
        $product->setLastEditedAt();
        $product->setTagsCollection($tagsCollection);
        $this->repository->save($product,$_SESSION['id']);

        return new Redirect('/products/show');

    }

    public function filterTemplate(): View
    {
        return new View('Products/filter.twig',
            ['categories' => $this->categories,
                'tags' => $this->definedTags,]);
    }

    public function filter(): View
    {

        if ($_GET['categoryId'] == 'all') $_GET['categoryId'] = null;
        $tags = array_map(fn($t) => $this->definedTags->getTagById($t), $_GET['tags']);
        $tagsCollection = new TagsCollection($tags);
        $filteredProductsCollection = $this->repository->filter($_SESSION['id'],$_GET['categoryId'],);


        return new View('Products/show.twig',
            ['productCollection' => $filteredProductsCollection,
                'categories' => $this->categories,
                'tags' => $this->definedTags,
                'userName' => Auth::user($_SESSION['id'])]);

    }

    public function delete(array $id): Redirect
    {
        $product = $this->repository->filterOneById($id['id'],$_SESSION['id']);
        $this->repository->delete($product, $_SESSION['id']);
        return new Redirect('/products/show');
    }

}