<?php

namespace app\Repositories;

use App\Models\Collections\ProductsCollection;
use App\Models\Collections\TagsCollection;
use App\Models\Product;
use App\Models\Tag;
use PDO;

class MysqlProducts_TagsRelationRepository
{
    private array $config;
    private PDO $pdo;
    private MysqlTagsRepository $tagsRepository;
    private TagsCollection $allTags;

    public function __construct(MysqlTagsRepository $tagsRepository , PDO $pdo)
    {
        $this->pdo = $pdo;

        $this->tagsRepository = $tagsRepository;
        $this->allTags = $this->tagsRepository->getAll();

    }


    public function save(Product $product): void
    {
        foreach ($product->getTagsCollection()->getTags() as $tag) {
            $sql = "INSERT INTO products_tags (product_id,tag_id) 
        VALUES  ('{$product->getId()}',
                 '{$tag->getTagId()}')";
            $this->pdo->exec($sql);
        }

    }

    public function getProductTagsCollection(string $productId): ?TagsCollection
    {
        $sql = "select tag_id from products_tags WHERE product_id ='$productId'";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();

        $tags = $statement->fetchAll(PDO::FETCH_CLASS);
        if (empty($tags)) return null;

        $tagsCollection = new TagsCollection();
        foreach ($tags as $tag) {
            $tagsCollection->add(new Tag($tag->tag_id, $this->allTags->getNameById($tag->tag_id)));
        }
        return $tagsCollection;
    }

    public function getTagProductsCollection(array $tagsId): ProductsCollection
    {

    }


    public function getAllTags(): TagsCollection
    {
        return $this->allTags;
    }


}