<?php

namespace app\Repositories;

use App\Models\Collections\ProductsCollection;
use App\Models\Product;
use PDO;
use App\Repositories\ProductsRepositoryInterface;


class MysqlProductsRepository implements ProductsRepositoryInterface
{
    private array $config;
    private PDO $pdo;

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

    }

    public function getAll(?int $id = null, ?string $category = null): ProductsCollection
    {
        if ($id !== null) {
            $sql = "SELECT * FROM products WHERE id='{$id}'";
        } elseif ($category !== null) {
            $sql = "SELECT * FROM products WHERE category='{$category}'";
        } else {
            $sql = "select * from products";
        }


        $statement = $this->pdo->prepare($sql);
        $statement->execute();

        $products = $statement->fetchAll(PDO::FETCH_CLASS);
        $productsCollection = new ProductsCollection();
        foreach ($products as $product) {
            $productsCollection->add(new Product(
                $product->name,
                $product->category,
                $product->amount,
                $product->lastEditedAt,
                $product->addedAt,
                $product->id
            ));
        }
        return $productsCollection;
    }

    public function save(Product $product): void
    {

        if ($product->getId() !== null) {
            $sql = "UPDATE products 
        SET name = '{$product->getName()}' ,
            category = '{$product->getCategory()}',
            amount = '{$product->getAmount()}' ,
          lastEditedAt = '{$product->getLastEditedAt()}'   
        WHERE id='{$product->getId()}'";
        } else {
            $sql = "INSERT INTO products (name,category,amount,addedAt,lastEditedAt) 
        VALUES  ('{$product->getName()}',
                 '{$product->getCategory()}',
                 '{$product->getAmount()}',
                 '{$product->getAddedAt()}',
                 '{$product->getLastEditedAt()}')";
        }


        $this->pdo->exec($sql);
    }

    public function delete(Product $product): void
    {
        $sql = "DELETE FROM products WHERE id={$product->getId()}";

        $this->pdo->exec($sql);
    }
}