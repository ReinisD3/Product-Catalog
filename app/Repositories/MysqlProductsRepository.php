<?php

namespace app\Repositories;

use App\Errors;
use App\Exceptions\RepositoryValidationException;
use App\Models\Collections\ProductsCollection;
use App\Models\Collections\TagsCollection;
use App\Models\Product;
use PDO;
use App\Repositories\ProductsRepositoryInterface;
use App\Repositories\MysqlCategoriesRepository;
use App\Repositories\MysqlTagsRepository;
use App\Repositories\MysqlProducts_TagsRelationRepository;


class MysqlProductsRepository implements ProductsRepositoryInterface
{
    private array $config;
    private PDO $pdo;
    private MysqlCategoriesRepository $categoriesRepository;
    private MysqlTagsRepository $tagsRepository;
    private MysqlProducts_TagsRelationRepository $products_TagsRelationRepository;
    private array $categories;
    private TagsCollection $definedTags;
    private Errors $errors;


    public function __construct()
    {
        $this->config = json_decode(file_get_contents('config.json',), true);
        try {
            $this->pdo = new PDO($this->config['connection'] . ';dbname=' . $this->config['name'],
                $this->config['username'],
                $this->config['password']);
        } catch (\PDOException $e) {
            die($e->getMessage());
        }

        $this->categoriesRepository = new MysqlCategoriesRepository($this->pdo);
        $this->categories = $this->categoriesRepository->getAll();

        $this->tagsRepository = new MysqlTagsRepository($this->pdo);
        $this->products_TagsRelationRepository = new MysqlProducts_TagsRelationRepository($this->tagsRepository, $this->pdo);

        $this->errors = new Errors();

    }

    public function getAll(?string $id = null, ?string $categoryId = null): ?ProductsCollection
    {

        $filter = "WHERE user = '{$_SESSION['id']}' ";
        if ($id !== null) {
            $filter .= "AND id='{$id}' ";
        } elseif ($categoryId !== null) {
            if (!isset($this->categories[$categoryId])) {
                $this->errors->add('categoryError','No such category');
                throw new RepositoryValidationException();
            }
            $filter .= "AND categoryId ='{$categoryId}' ";
        }

        $sql = "SELECT * FROM products " . $filter;

        $statement = $this->pdo->prepare($sql);
        $statement->execute();

        $products = $statement->fetchAll(PDO::FETCH_CLASS);


        $productsCollection = new ProductsCollection();

        if (empty($products)) {
            return null;
        }

        foreach ($products as $product) {
            $productsCollection->add(new Product(
                $product->name,
                $product->categoryId,
                $product->amount,
                $product->user,
                $this->products_TagsRelationRepository->getProductTagsCollection($product->id),
                $product->lastEditedAt,
                $product->addedAt,
                $product->id,
                $this->categoriesRepository->get($product->categoryId)
            ));
        }
        return $productsCollection;
    }

    public function save(Product $product): void
    {

        if (!isset($this->categories[$product->getCategoryId()])) {
            $this->errors->add('categoryError','No such category');
            throw new RepositoryValidationException();
        }


        if ($this->getAll($product->getId()) !== null) {
            $sql = "UPDATE products 
        SET name = '{$product->getName()}' ,
            categoryId = '{$product->getCategoryId()}',
            amount = '{$product->getAmount()}' ,
          lastEditedAt = '{$product->getLastEditedAt()}'            
        WHERE id='{$product->getId()}'";
        } else {
            $sql = "INSERT INTO products (name,categoryId,amount,addedAt,lastEditedAt,id,user) 
        VALUES  ('{$product->getName()}',
                 '{$product->getCategoryId()}',
                 '{$product->getAmount()}',
                 '{$product->getAddedAt()}',
                 '{$product->getLastEditedAt()}',
                 '{$product->getId()}',
                 '{$product->getUser()}')";
        }

        $this->pdo->exec($sql);
        $this->products_TagsRelationRepository->save($product);
    }

    public function delete(Product $product): void
    {

        $sql = "DELETE FROM products WHERE id= '{$product->getId()}'";

        $this->pdo->exec($sql);
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function definedTagsCollection(): TagsCollection
    {
        return $this->products_TagsRelationRepository->getAllTags();
    }
}