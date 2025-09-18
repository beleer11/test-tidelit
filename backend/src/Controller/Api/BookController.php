<?php

namespace App\Controller\Api;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/books')]
class BookController extends AbstractController
{
    #[Route('', name: 'books_list', methods: ['GET'])]
    public function list(BookRepository $bookRepo): JsonResponse
    {
        $books = $bookRepo->findAllWithAverageRating();
        return $this->json([
            'status' => 200,
            'message' => 'Listado de libros obtenido correctamente',
            'data' => $books
        ]);
    }

    #[Route('/{id}', name: 'books_detail', methods: ['GET'])]
    public function detail(BookRepository $bookRepo, int $id): JsonResponse
    {
        $book = $bookRepo->find($id);
        if (!$book) {
            return $this->json(['error' => 'Libro no encontrado'], 404);
        }

        return $this->json([
            'status' => 200,
            'message' => 'Detalle del libro obtenido correctamente',
            'data' => [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'author' => $book->getAuthor(),
                'published_year' => $book->getPublishedYear()
            ]
        ]);
    }
}
