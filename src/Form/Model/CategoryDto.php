<?php

namespace App\Form\Model;

use App\Entity\Book;
use App\Entity\Category;

class CategoryDto
{
    public $id;
    public $name;

    public static function createFromCategory(Category $category): CategoryDto
    {
        $dto = new self();
        $dto->id = $category->getId();
        $dto->name = $category->getName();
        return $dto;
    }
}