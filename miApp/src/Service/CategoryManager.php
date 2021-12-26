<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryManager
{
    private EntityManagerInterface $em;
    private CategoryRepository $categoryRepository;

    public function __construct(
        EntityManagerInterface $em,
        CategoryRepository     $categoryRepository)
    {
        $this->em = $em;
        $this->categoryRepository = $categoryRepository;
    }

    public function find(int $id): ?Category
    {
        return $this->categoryRepository->find($id);
    }

    public function create(): Category
    {
        $category = new Category();
        return $category;
    }

    public function persist(Category $category): Category
    {
        $this->em->persist($category);
        return $category;
    }

    public function save(Category $category): Category
    {
        $this->persist($category);
        $this->em->flush();
        return $category;
    }

    public function reload(Category $category): Category
    {
        $this->em->refresh($category);
        return $category;
    }

    public function getRepository(): CategoryRepository
    {
        return $this->categoryRepository;
    }
}