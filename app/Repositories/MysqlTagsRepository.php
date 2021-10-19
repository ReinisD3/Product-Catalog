<?php

namespace app\Repositories;

use App\Models\Collections\TagsCollection;
use App\Models\Tag;
use PDO;


class MysqlTagsRepository
{
    private array $config;
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;

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
}