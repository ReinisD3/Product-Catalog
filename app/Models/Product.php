<?php

namespace app\Models;

use Carbon\Carbon;

class Product
{
    private string $name;
    private string $category;
    private int $amount;
    private ?string $addedAt;
    private ?string $lastEditedAt;
    private ?int $id;

    public function __construct(string $name,
                                string $category,
                                int $amount,
                                ?string $lastEditedAt = null,
                                ?string $addedAt = null,
                                ?int $id = null )
    {
        $this->name = $name;
        $this->category = $category;
        $this->amount = $amount;
        $this->addedAt = $addedAt ?? Carbon::now();
        $this->lastEditedAt = $lastEditedAt;
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCategory(): string
    {
        return $this->category;
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }
    public function setLastEditedAt(): void
    {
        $this->lastEditedAt = Carbon::now();
    }
}