<?php

namespace app\Models;

use app\Models\Collections\TagsCollection;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

class Product
{
    private string $name;
    private int $categoryId;
    private int $amount;
    private ?string $addedAt;
    private ?string $lastEditedAt;
    private ?string $id;
    private ?string $category;
    private ?TagsCollection $tagsCollection;
    private string $user;

    public function __construct(string          $name,
                                int             $categoryId,
                                int             $amount,
                                ?string         $user = null,
                                ?TagsCollection $tagsCollection = null,
                                ?string         $lastEditedAt = null,
                                ?string         $addedAt = null,
                                ?string         $id = null,
                                ?string         $category = null)
    {
        $this->name = $name;
        $this->categoryId = $categoryId;
        $this->amount = $amount;
        $this->addedAt = $addedAt ?? Carbon::now();
        $this->lastEditedAt = $lastEditedAt;
        $this->id = $id ?? Uuid::uuid4();
        $this->category = $category;
        $this->tagsCollection = $tagsCollection;
        $this->user = $user ?? 'public';
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getAddedAt(): string
    {
        return $this->addedAt;
    }

    public function getLastEditedAt(): ?string
    {
        return $this->lastEditedAt;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function setLastEditedAt(): void
    {
        $this->lastEditedAt = Carbon::now();
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function getTagsCollection(): ?TagsCollection
    {
        return $this->tagsCollection;
    }

    public function setTagsCollection(?TagsCollection $tagsCollection): void
    {
        $this->tagsCollection = $tagsCollection;
    }

    public function getUser(): string
    {
        return $this->user;
    }

}