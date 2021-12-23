<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function createBook(
        Request $request,
        EntityManagerInterface $em): JsonResponse
    {
        $postData = json_decode($request->getContent(), true);
        $responseData = [
            "result" => [
                "success" => true,
                "message" => "",
            ],
            "data" => [],
        ];
        $code = 200;

        if(empty($postData['title'])){
            $responseData["result"]["success"] = false;
            $responseData["result"]["message"] = "El título está vacío";
            $code = 400;
        }else{
            $responseData["result"]["message"] = "Libro creado correctamente";

            $book = new Book();
            $book->setTitle($postData['title']);
            $book->setImage($postData['image']);

            $em->persist($book);
            $em->flush();
        }

        $response = new JsonResponse();
        $response->setData($responseData);
        $response->setStatusCode($code);
        return $response;
    }

    /**
     * @param Request $request
     * @param BookRepository $bookRepository
     * @return JsonResponse
     */
    public function getBookById(
        Request $request,
        BookRepository $bookRepository): JsonResponse
    {
        $id = (int)$request->get('id');
        $response = new JsonResponse();
        $responseData = [
            "result" => [
                "success" => true,
                "message" => "",
            ],
            "data" => [],
        ];
        $code = 200;

        if(!is_int($id) || $id === 0){
            $responseData["result"]["success"] = false;
            $responseData["result"]["message"] = "Id incorrecto";
            $response->setData($responseData);
            $response->setStatusCode(400);
            return $response;
        }

        $book = $bookRepository->findBy(["id" => $id]);

        if ($book) {
            $book = reset($book);
            $responseData["result"]["message"] = "Libro encontrado";
            $responseData["data"] = [
                "Título" => $book->getTitle(),
                "Imagen" => $book->getImage(),
            ];
        } else {
            $responseData["result"]["success"] = false;
            $responseData["result"]["message"] = "Libro no encontrado";
            $code = 404;
        }

        $response->setData($responseData);
        $response->setStatusCode($code);
        return $response;
    }

    /**
     * @Route("/books", methods={"GET"}, name="getBooksList")
     */
    public function listBooks(BookRepository $bookRepository): JsonResponse
    {
        $response = new JsonResponse();
        $responseData = [
            "result" => [
                "success" => true,
                "message" => "",
            ],
            "data" => [],
        ];
        $code = 200;

        $books = $bookRepository->findAll();

        if (!$books) {
            $responseData["result"]["success"] = false;
            $responseData["result"]["message"] = "Lista de libros vacía";
            $code = 400;
        } else {
            $responseData["result"]["message"] = "Lista de libros";

            foreach($books as $book){
                $responseData["data"][] = [
                    "id"     => $book->getId(),
                    "Título" => $book->getTitle(),
                    "Imagen" => $book->getImage(),
                ];
            }
        }

        $response->setData($responseData);
        $response->setStatusCode($code);
        return $response;
    }
}