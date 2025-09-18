<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 */
class ReviewRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Review::class);
        $this->entityManager = $entityManager;
    }

    public function save(Review $review, bool $flush = true): void
    {
        $this->entityManager->persist($review);
        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function getAverageRatingByBook(int $bookId): ?float
    {
        return $this->createQueryBuilder('r')
            ->select('AVG(r.rating) as avgRating')
            ->where('r.book = :bookId')
            ->setParameter('bookId', $bookId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
