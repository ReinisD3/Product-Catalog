<?php

namespace App\Controllers;

use App\Auth;
use App\Models\Collections\TagsCollection;
use App\Models\Product;
use App\Redirect;
use App\Repositories\CategoriesRepositoryInterface;
use App\Repositories\MysqlCategoriesRepository;
use App\Repositories\MysqlProductsRepository;
use App\Repositories\MysqlTagsRepository;
use App\Repositories\ProductsRepositoryInterface;
use App\Repositories\TagsRepositoryInterface;
use Twig\Environment;


class ProductsController
{
    private ProductsRepositoryInterface $repository;
    private CategoriesRepositoryInterface $categoriesRepository;
    private TagsRepositoryInterface $tagsRepository;
    private Environment $twig;
    private TagsCollection $definedTags;
    private array $categories;


    public function __construct(MysqlProductsRepository $repository,
                                MysqlCategoriesRepository $categoriesRepository,
                                MysqlTagsRepository $tagsRepository,
                                Environment $twig)
    {
        $this->repository = $repository;
        $this->tagsRepository = $tagsRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->twig = $twig;
        $this->definedTags = $this->tagsRepository->getAll();
        $this->categories = $this->categoriesRepository->getAll();


    }

    public function index(): void
    {
        echo  $this->twig->render('Products/index.twig');
    }

    public function show(): void
    {
        $productCollection = $this->repository->getAll($_SESSION['id']);
        echo  $this->twig->render('Products/show.twig',['productCollection' => $productCollection,
            'categories' => $this->categories,
            'tags' => $this->definedTags,
            'userName' => Auth::user($_SESSION['id'])]);
    }

    public function addTemplate(): void
    {

        echo  $this->twig->render('Products/add.twig',
            ['categories' => $this->categories,
                'userName' => Auth::user($_SESSION['id']),
                'tags' => $this->definedTags,
                'errors' => $_SESSION['errors']]);
    }

    public function add(): void
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

        Redirect::url('/products/show');


    }

    public function editTemplate(array $productId): void
    {
        $editProduct = $this->repository->filterOneById($productId['id'],$_SESSION['id']);

        echo  $this->twig->render('Products/edit.twig',
            ['product' => $editProduct,
                'categories' => $this->categories,
                'tags' => $this->definedTags,
                'errors' => $_SESSION['errors'],
                'userName' => Auth::user($_SESSION['id'])]);
    }


    public function edit(array $productId): void
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

        Redirect::url('/products/show');

    }

    public function filterTemplate(): void
    {
        echo  $this->twig->render('Products/filter.twig',
            ['categories' => $this->categories,
                'tags' => $this->definedTags,
                'userName' => Auth::user($_SESSION['id'])]);
    }

    public function filter(): void
    {

        if ($_GET['categoryId'] == 'all') $_GET['categoryId'] = null;
        $tags = array_map(fn($t) => $this->definedTags->getTagById($t), $_GET['tags']);
        $tagsCollection = new TagsCollection($tags);
        $filteredProductsCollection = $this->repository->filter($_SESSION['id'],$_GET['categoryId'],$tagsCollection);


        echo  $this->twig->render('Products/show.twig',
            ['productCollection' => $filteredProductsCollection,
                'categories' => $this->categories,
                'tags' => $this->definedTags,
                'userName' => Auth::user($_SESSION['id'])]);

    }

    public function delete(array $id): void
    {
        $product = $this->repository->filterOneById($id['id'],$_SESSION['id']);
        $this->repository->delete($product, $_SESSION['id']);
        Redirect::url('/products/show');
    }

}