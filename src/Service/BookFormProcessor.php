<?php

namespace App\Service;

use App\Entity\Book;
use App\Form\Model\AuthorDto;
use App\Form\Model\BookDto;
use App\Form\Model\CategoryDto;
use App\Form\Type\BookFormType;
use Doctrine\Common\Collections\ArrayCollection;
use League\Flysystem\FilesystemException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class BookFormProcessor
{
    private BookManager $bookManager;
    private CategoryManager $categoryManager;
    private AuthorManager $authorManager;
    private FileUploader $fileUploader;
    private FormFactoryInterface $formFactory;

    /**
     * @param BookManager $bookManager
     * @param CategoryManager $categoryManager
     * @param AuthorManager $authorManager
     * @param FileUploader $fileUploader
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        BookManager $bookManager,
        CategoryManager $categoryManager,
        AuthorManager $authorManager,
        FileUploader $fileUploader,
        FormFactoryInterface $formFactory)
    {
        $this->authorManager = $authorManager;
        $this->categoryManager = $categoryManager;
        $this->bookManager = $bookManager;
        $this->fileUploader = $fileUploader;
        $this->formFactory = $formFactory;
    }

    /**
     * @param Book $book
     * @param Request $request
     * @return array
     * @throws FilesystemException
     */
    public function __invoke(
        Book $book,
        Request $request): array
    {
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

        $form = $this->formFactory->create(BookFormType::class, $bookDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
//            return new Response('', Response::HTTP_BAD_REQUEST);
            return [null, 'El formulario no se ha enviado'];
        }

        if ($form->isValid()) {
            /*
             * Borrar categor??as
             * Una vez que el formulario ha sido enviado y es v??lido, $bookDto pasa a tener las
             * categor??as que el usuario ha enviado a trav??s de la API
             */
            foreach ($originalCategories as $originalCategoryDto) {
                if (!in_array($originalCategoryDto, $bookDto->categories)) {
                    $category = $this->categoryManager->find($originalCategoryDto->id);
                    $book->removeCategory($category);
                }
            }

            // A??adir nuevas categor??as
            foreach ($bookDto->categories as $newCategoryDto) {
                if (!$originalCategories->contains($newCategoryDto)) {
                    /*
                     * Dado que el usuario puede crear categor??as desde el formulario, es posible que la
                     * categor??a no tenga id, le asignamos 0 para que no encuentre categor??a sin dar error
                     */
                    $category = $this->categoryManager->find($newCategoryDto->id ?? 0);
                    if (!$category) {
                        $category = $this->categoryManager->create();
                        $category->setName($newCategoryDto->name);
                        $this->categoryManager->persist($category);
                    }
                    $book->addCategory($category);
                }
            }

            /**
             * Gestionar Autores
             */
            foreach($originalAuthors as $originalAuthorDto){
                if(!in_array($originalAuthorDto, $bookDto->authors)){
                    $author = $this->authorManager->find($originalAuthorDto->id);
                    $book->removeAuthor($author);
                }
            }

            foreach($bookDto->authors as $newAuthorDto){
                if(!$originalAuthors->contains($newAuthorDto)){
                    $author = $this->authorManager->find($newAuthorDto->id ?? 0);
                    if(!$author){
                        $author = $this->authorManager->create();
                        $author->setName($newAuthorDto->name);
                        $this->authorManager->persist($author);
                    }
                    $book->addAuthor($author);
                }
            }

            $book->setTitle($bookDto->title);
            if ($bookDto->base64Image) {
                $filename = $this->fileUploader->uploadBase64File($bookDto->base64Image);
                $book->setImage($filename);
            }
            $this->bookManager->persist($book);
            $this->bookManager->save($book);
            /**
             * Doctrine est?? devolviendo las categor??as como un objeto, ya que al a??adir y eliminar
             * conserva los ??ndices. Si tenemos las categor??as ??ndice (no Id) 0, 1 y 2 y eliminamos la 1,
             * Doctrine nos devolver?? las dos categor??as con los ??ndices 0 y 2 en lugar de 0 y 1. Para evitar
             * esto, refrescamos:
             */
            $this->bookManager->reload($book);
            return [$book, null];
        }
        return [null, 'Error'];
    }
}