<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $r)
    {
        parent::__construct($r, Book::class);
    }

    public function findAllWithAverageRating(): array
    {
        $qb = $this->createQueryBuilder('b')
            ->select('b.id, b.title, b.author, b.publishedYear AS published_year, AVG(r.rating) AS average_rating')
            ->leftJoin('b.reviews', 'r')
            ->groupBy('b.id')
            ->orderBy('b.title', 'ASC');

        $books = $qb->getQuery()->getArrayResult();

        return array_map(function ($book) {
            $book['average_rating'] = $book['average_rating'] !== null
                ? round($book['average_rating'], 1)
                : null;
            return $book;
        }, $books);
    }
}
