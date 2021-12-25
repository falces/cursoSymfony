<?php

namespace App\Form\Model;

use App\Entity\Book;

class BookDto
{
    public $title;
    public $base64Image;
    public $categories;
    public $authors;

    public function __construct()
    {
        $this->categories = [];
        $this->authors    = [];
    }

    public static function createFromBook(Book $book): BookDto
    {
        $dto = new self();
        $dto->title = $book->getTitle();
        return $dto;
    }
}