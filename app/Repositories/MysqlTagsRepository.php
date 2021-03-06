<?php

namespace App\Repositories;

use App\Models\Collections\TagsCollection;
use App\Models\Product;
use App\Models\Tag;
use PDO;

class MysqlTagsRepository implements TagsRepositoryInterface
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

    public function getAll(): TagsCollection
    {
        $sql = "select * from tags";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();

        $tags = $statement->fetchAll(PDO::FETCH_CLASS);

        $allTagCollection = new TagsCollection();
        foreach ($tags as $tag) {
            $allTagCollection->add(new Tag($tag->tag_id, $tag->name));
        }
        return $allTagCollection;

    }

    public function save(Product $product): void
    {
        /** @var Product $product */

        $sql = "DELETE FROM products_tags WHERE product_id='{$product->getId()}'";
        $this->pdo->prepare($sql)->execute();


        foreach ($product->getTagsCollection()->getTags() as $tag) {
            $sql = "INSERT INTO products_tags (product_id,tag_id) 
        VALUES  ('{$product->getId()}',
                 '{$tag->getId()}')";
            $this->pdo->exec($sql);
        }
    }

    public function getNameById(string $id): ?string
    {
        $sql = "SELECT * FROM tags WHERE tag_id = '$id'";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $tagName = $statement->fetchAll(PDO::FETCH_CLASS);
        return $tagName[0]->name ?? null;
    }


    public function tagsIsDefined(array $tags):bool
    {
        foreach ($tags as $tag)
        {
            if($this->getNameById($tag['id']) == null) return false;
        }
        return true;
    }
}