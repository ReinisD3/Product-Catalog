<?php

namespace app\Repositories;

use PDO;

class MysqlCategoriesRepository
{
    private array $config;
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
      $this->pdo = $pdo;
    }

    public function get(int $categoryId): string
    {
        $sql = "select * from product_categories Where categoryid = '$categoryId'";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();

        $products = $statement->fetchAll(PDO::FETCH_CLASS);

        return $products[0]->name;
    }

    public function getAll(): array
    {
        $sql = "select * from product_categories";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();

        $categories = $statement->fetchAll(PDO::FETCH_CLASS);
        $categoriesKeyed = [];
        foreach ($categories as $category)
        {
            $categoriesKeyed[$category->categoryId] = $category;
        }
        return $categoriesKeyed;

    }
}