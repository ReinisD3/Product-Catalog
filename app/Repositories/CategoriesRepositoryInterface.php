<?php

namespace App\Repositories;

interface CategoriesRepositoryInterface
{
    public function getNameById(int $categoryId): ?string;
    public function getAll(): array;

}