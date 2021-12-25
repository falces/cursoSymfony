<?php

namespace App\Form\Model;

use App\Entity\Author;

class AuthorDto
{
    public $id;
    public $name;

    /**
     * @param Author $author
     * @return AuthorDto
     */
    public static function createFromAuthor(Author $author): AuthorDto
    {
        $dto = new self();
        $dto->id = $author->getId();
        $dto->name = $author->getName();
        return $dto;
    }
}