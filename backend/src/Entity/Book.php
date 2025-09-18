<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Book
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $author = null;

    #[ORM\Column(type: "integer")]
    private ?int $publishedYear = null;

    #[ORM\OneToMany(mappedBy: "book", targetEntity: Review::class, cascade: ["persist", "remove"])]
    private Collection $reviews;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTitle(): ?string
    {
        return $this->title;
    }
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }
    public function setAuthor(string $author): self
    {
        $this->author = $author;
        return $this;
    }

    public function getPublishedYear(): ?int
    {
        return $this->publishedYear;
    }
    public function setPublishedYear(int $year): self
    {
        $this->publishedYear = $year;
        return $this;
    }

    public function getReviews(): Collection
    {
        return $this->reviews;
    }
}
