<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReviewRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Review
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: "reviews")]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "book_id es requerido")]
    private ?Book $book = null;

    #[ORM\Column(type: "integer")]
    #[Assert\NotNull]
    #[Assert\Range(min: 1, max: 5, notInRangeMessage: "rating debe estar entre {{ min }} y {{ max }}")]
    private int $rating;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "comment no puede estar vacío")]
    private string $comment;

    #[ORM\Column(type: "datetime_immutable")]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\PrePersist]
    public function prePersistSetCreatedAt(): void
    {
        if (!$this->createdAt) {
            $this->createdAt = new \DateTimeImmutable();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }
    public function setBook(?Book $b): self
    {
        $this->book = $b;
        return $this;
    }

    public function getRating(): int
    {
        return $this->rating;
    }
    public function setRating(int $r): self
    {
        $this->rating = $r;
        return $this;
    }

    public function getComment(): string
    {
        return $this->comment;
    }
    public function setComment(string $c): self
    {
        $this->comment = $c;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function setCreatedAt(\DateTimeImmutable $d): self
    {
        $this->createdAt = $d;
        return $this;
    }
}
