<?php

namespace App\Repositories;

use PDO;


class MysqlCategoriesRepository implements CategoriesRepositoryInterface
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

    public function getNameById(int $categoryId): ?string
    {
        $sql = "select * from product_categories Where categoryid = '$categoryId'";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();

        $products = $statement->fetchAll(PDO::FETCH_CLASS);

        return $products[0]->name ?? null;
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