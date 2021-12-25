<?php

namespace App\Service;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;

class AuthorManager
{
    private EntityManagerInterface $em;
    private AuthorRepository $authorRepository;

    /**
     * @param EntityManagerInterface $em
     * @param AuthorRepository $authorRepository
     */
    public function __construct(
        EntityManagerInterface $em,
        AuthorRepository       $authorRepository)
    {
        $this->em = $em;
        $this->authorRepository = $authorRepository;
    }

    /**
     * @param int $id
     * @return Author|null
     */
    public function find(int $id): ?Author
    {
        return $this->authorRepository->find($id);
    }

    /**
     * @return Author
     */
    public function create(): Author
    {
        $author = new Author();
        return $author;
    }

    /**
     * @param Author $author
     * @return Author
     */
    public function persist(Author $author): Author
    {
        $this->em->persist($author);
        return $author;
    }

    /**
     * @param Author $author
     * @return Author
     */
    public function save(Author $author): Author
    {
        $this->persist($author);
        $this->em->flush();
        return $author;
    }

    /**
     * @param Author $author
     * @return Author
     */
    public function reload(Author $author): Author
    {
        $this->em->refresh($author);
        return $author;
    }

    /**
     * @return AuthorRepository
     */
    public function getRepository(): AuthorRepository
    {
        return $this->authorRepository;
    }
}