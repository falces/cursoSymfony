<?php

namespace App\Controller\Api;

use App\Service\BookFormProcessor;
use App\Service\BookManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use League\Flysystem\FilesystemException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BooksController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getActions(
        BookManager $bookManager
    ): array {
        return $bookManager->getRepository()->findAll();
    }

    /**
     * @Rest\Post(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     * @throws FilesystemException
     */
    public function postAction(
        BookManager       $bookManager,
        BookFormProcessor $bookFormProcessor,
        Request           $request
    ) {
        $book = $bookManager->create();
        [$book, $error] = ($bookFormProcessor)($book, $request);

        $statusCode = $book ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $book ?? $error;
        return View::create($data, $statusCode);
    }

    /**
     * @Rest\Post(path="/books/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     * @throws FilesystemException
     */
    public function editAction(
        int               $id,
        BookFormProcessor $bookFormProcessor,
        BookManager       $bookManager,
        Request           $request
    ) {
        $book = $bookManager->find($id);
        if (!$book) {
            return View::create('Libro no encontrado', Response::HTTP_BAD_REQUEST);
        }
        [$book, $error] = ($bookFormProcessor)($book, $request);

        $statusCode = $book ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $book ?? $error;
        return View::create($data, $statusCode);
    }

    /**
     * @Rest\Delete(path="/books/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function deleteAction(
        int               $id,
        BookManager       $bookManager
    ) {
        $book = $bookManager->find($id);
        if (!$book) {
            return View::create('Libro no encontrado', Response::HTTP_BAD_REQUEST);
        }
        $bookManager->delete($book);

        return View::create('Libro borrado', Response::HTTP_NO_CONTENT);
    }
}