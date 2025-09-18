<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Book;
use App\Entity\Review;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $booksData = [
            ['El Arte de Programar', 'Donald Knuth', 1968],
            ['Clean Code', 'Robert C. Martin', 2008],
            ['Refactoring', 'Martin Fowler', 1999],
        ];

        $books = [];
        foreach ($booksData as [$title, $author, $year]) {
            $b = new Book();
            $b->setTitle($title)->setAuthor($author)->setPublishedYear($year);
            $manager->persist($b);
            $books[] = $b;
        }

        $reviews = [
            [$books[0], 5, 'Obra maestra.'],
            [$books[0], 4, 'Muy profundo.'],
            [$books[1], 5, 'Imprescindible para dev.'],
            [$books[1], 3, 'Bueno pero básico.'],
            [$books[2], 4, 'Muy práctico.'],
            [$books[2], 2, 'Esperaba más ejemplos.'],
        ];

        foreach ($reviews as [$book, $rating, $comment]) {
            $r = new Review();
            $r->setBook($book)->setRating($rating)->setComment($comment);
            $manager->persist($r);
        }

        $manager->flush();
    }
}
