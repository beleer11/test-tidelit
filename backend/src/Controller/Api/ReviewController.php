<?php

namespace App\Controller\Api;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/reviews')]
class ReviewController extends AbstractController
{
    #[Route('', name: 'reviews_list', methods: ['GET'])]
    public function list(Request $request, ReviewRepository $reviewRepo): JsonResponse
    {
        $bookId = $request->query->get('book_id');
        if (!$bookId) {
            return $this->json(['error' => 'book_id es requerido'], 400);
        }

        $reviews = $reviewRepo->findBy(['book' => $bookId]);
        $data = array_map(fn($r) => [
            'id' => $r->getId(),
            'rating' => $r->getRating(),
            'comment' => $r->getComment(),
            'createdAt' => $r->getCreatedAt()->format('Y-m-d H:i:s')
        ], $reviews);

        return $this->json([
            'status' => 200,
            'message' => 'Reseñas obtenidas correctamente',
            'data' => $data
        ]);
    }

    #[Route('', name: 'reviews_create', methods: ['POST'])]
    public function create(Request $request, ReviewRepository $reviewRepo, BookRepository $bookRepo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $book = $bookRepo->find($data['book_id'] ?? 0);
        if (!$book) {
            return $this->json(['error' => 'Libro no encontrado'], 404);
        }

        $review = new Review();
        $review->setBook($book);
        $review->setRating($data['rating']);
        $review->setComment($data['comment']);
        $review->setCreatedAt(new \DateTimeImmutable());

        $reviewRepo->save($review);

        return $this->json([
            'status' => 201,
            'message' => 'Reseña creada correctamente'
        ], 201);
    }
}
