<?php

namespace app\Repositories;

use App\Models\Collections\ProductsCollection;
use App\Models\Collections\TagsCollection;
use App\Models\Product;
use App\Models\Tag;
use PDO;
use App\Repositories\ProductsRepositoryInterface;
use App\Repositories\MysqlCategoriesRepository;
use App\Repositories\MysqlTagsRepository;


class MysqlProductsRepository implements ProductsRepositoryInterface
{
    private array $config;
    private PDO $pdo;
    private MysqlCategoriesRepository $categoriesRepository;
    private MysqlTagsRepository $tagsRepository;

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
        $this->categoriesRepository = new MysqlCategoriesRepository();
        $this->tagsRepository = new MysqlTagsRepository();

    }

    public function filter(string $userId , ?string $categoryId = null,?TagsCollection $tagsCollection=null): ProductsCollection
    {

        $filterSql = "WHERE user = '{$userId}' ";
        if(isset($categoryId)) $filterSql .= "AND categoryId ='{$categoryId}'";
        if(isset($tagsCollection))
        {
            $filteredProductIds = $this->tagsRepository->getProductIdByTagsCollection($tagsCollection);
            foreach ($filteredProductIds as $id)
            {
                $filterSql .= "AND id='{$id->id}'";
            }
        }
        $sql = "SELECT * FROM products ".$filterSql;
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $products = $statement->fetchAll(PDO::FETCH_CLASS);


        return $this->makeProductCollectionFromMySQLFetch_Class($products);
    }

    public function filterOneById(string $id, string $user): ?Product
    {
        $sql = "SELECT * FROM products WHERE id='{$id}' AND user = '{$user}'";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $products = $statement->fetchAll(PDO::FETCH_CLASS);
        $collection = $this->makeProductCollectionFromMySQLFetch_Class($products);
        return $collection !== null ? $collection->getProducts()[0] : null;

    }

    public function getAll(?string $userId = null): ProductsCollection
    {

        $sql = "SELECT * FROM products WHERE user = '{$userId}'";
        $products = $this->executeQueryFetchClass($sql);
        return $this->makeProductCollectionFromMySQLFetch_Class($products);
    }

    public function save(Product $product, string $userId): void
    {

        if ($this->filterOneById($product->getId(),$userId) !== null) {
            $sql = "UPDATE products 
        SET name = '{$product->getName()}' ,
            categoryId = '{$product->getCategoryId()}',
            amount = '{$product->getAmount()}' ,
          lastEditedAt = '{$product->getLastEditedAt()}'            
        WHERE id='{$product->getId()}' AND user = '{$userId}'";
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
        $this->tagsRepository->save($product);
    }

    public function delete(Product $product, string $userId): void
    {

        $sql = "DELETE FROM products WHERE id= '{$product->getId()}' AND user= '{$userId}'";

        $this->pdo->exec($sql);
    }

    public function getProductTagsCollection(string $productId): TagsCollection
    {
        $sql = "SELECT tag_id FROM products_tags WHERE product_id ='$productId'";
        $tags = $this->executeQueryFetchClass($sql);

        $tagsCollection = new TagsCollection();
        if (empty($tags)) return $tagsCollection;
        foreach ($tags as $tag) {
            $tagsCollection->add(new Tag($tag->tag_id, $this->tagsRepository->getNameById($tag->tag_id)));
        }
        return $tagsCollection;
    }

    public function makeProductCollectionFromMySQLFetch_Class(?array $products = null): ?ProductsCollection
    {
        $productsCollection = new ProductsCollection();

        if ($products == null) {
            return null;
        }
        foreach ($products as $product) {
            $productsCollection->add(new Product(
                $product->name,
                $product->categoryId,
                $product->amount,
                $product->user,
                $this->getProductTagsCollection($product->id),
                $product->lastEditedAt,
                $product->addedAt,
                $product->id,
                $this->categoriesRepository->getNameById($product->categoryId)
            ));
        }
        return $productsCollection;
    }
    public function executeQueryFetchClass(string $sql):array
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_CLASS);
    }

}