<?php

namespace App\Controller\Api;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Form\Model\AuthorDto;
use App\Form\Model\BookDto;
use App\Form\Model\CategoryDto;
use App\Form\Type\BookFormType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use App\Service\FileUploader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use League\Flysystem\FilesystemException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BooksController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getActions(BookRepository $bookRepository): array
    {
        return $bookRepository->findAll();
    }

    /**
     * @Rest\Post(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     * @throws FilesystemException
     */
    public function postAction(
        EntityManagerInterface $em,
        Request $request,
        FileUploader $fileUploader)
    {
        $bookDto = new BookDto();
        $form = $this->createForm(BookFormType::class, $bookDto);
        $form->handleRequest($request);

        if(!$form->isSubmitted()){
            return new Response('', Response::HTTP_BAD_REQUEST);
        }

        if ($form->isValid()) {
            $book = new Book();
            $book->setTitle($bookDto->title);
            if(!empty($bookDto->base64Image)){
                $fileName = $fileUploader->uploadBase64File($bookDto->base64Image);
                $book->setImage($fileName);
            }

            $em->persist($book);
            $em->flush();

            return $book;
        }
        return $form;
    }

    /**
     * @Rest\Post(path="/books/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     * @throws FilesystemException
     */
    public function editAction(
        int $id,
        EntityManagerInterface $em,
        BookRepository $bookRepository,
        CategoryRepository $categoryRepository,
        AuthorRepository $authorRepository,
        Request $request,
        FileUploader $fileUploader
    )
    {
        $book = $bookRepository->find($id);
        if (!$book) {
            throw $this->createNotFoundException('Libro no encontrado');
        }

        $bookDto = BookDto::createFromBook($book);

        $originalCategories = new ArrayCollection();
        foreach ($book->getCategories() as $category) {
            $categoryDto = CategoryDto::createFromCategory($category);
            $bookDto->categories[] = $categoryDto;
            $originalCategories->add($categoryDto);
        }

        $originalAuthors = new ArrayCollection();
        foreach($book->getAuthor() as $author){
            $authorDto = AuthorDto::createFromAuthor($author);
            $bookDto->authors[] = $authorDto;
            $originalAuthors->add($authorDto);
        }

        $form = $this->createForm(BookFormType::class, $bookDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }

        if ($form->isValid()) {
            /*
             * Borrar categorías
             * Una vez que el formulario ha sido enviado y es válido, $bookDto pasa a tener las
             * categorías que el usuario ha enviado a través de la API
             */
            foreach ($originalCategories as $originalCategoryDto) {
                if (!in_array($originalCategoryDto, $bookDto->categories)) {
                    $category = $categoryRepository->find($originalCategoryDto->id);
                    $book->removeCategory($category);
                }
            }

            // Añadir nuevas categorías
            foreach ($bookDto->categories as $newCategoryDto) {
                if (!$originalCategories->contains($newCategoryDto)) {
                    /*
                     * Dado que el usuario puede crear categorías desde el formulario, es posible que la
                     * categoría no tenga id, le asignamos 0 para que no encuentre categoría sin dar error
                     */
                    $category = $categoryRepository->find($newCategoryDto->id ?? 0);
                    if (!$category) {
                        $category = new Category();
                        $category->setName($newCategoryDto->name);
                        $em->persist($category);
                    }
                    $book->addCategory($category);
                }
            }

            /**
             * Gestionar Autores
             */
            foreach($originalAuthors as $originalAuthorDto){
                if(!in_array($originalAuthorDto, $bookDto->authors)){
                    $author = $authorRepository->find($originalAuthorDto->id);
                    $book->removeAuthor($author);
                }
            }

            foreach($bookDto->authors as $newAuthorDto){
                if(!$originalAuthors->contains($newAuthorDto)){
                    $author = $authorRepository->find($newAuthorDto->id ?? 0);
                    if(!$author){
                        $author = new Author();
                        $author->setName($newAuthorDto->name);
                        $em->persist($author);
                    }
                    $book->addAuthor($author);
                }
            }

            $book->setTitle($bookDto->title);
            if ($bookDto->base64Image) {
                $filename = $fileUploader->uploadBase64File($bookDto->base64Image);
                $book->setImage($filename);
            }
            $em->persist($book);
            $em->flush();
            /**
             * Doctrine está devolviendo las categorías como un objeto, ya que al añadir y eliminar
             * conserva los índices. Si tenemos las categorías índice (no Id) 0, 1 y 2 y eliminamos la 1,
             * Doctrine nos devolverá las dos categorías con los índices 0 y 2 en lugar de 0 y 1. Para evitar
             * esto, refrescamos:
             */
            $em->refresh($book);
            return $book;
        }

        return $form;
    }
}