<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['review:read', 'book:read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'float')]
    #[Groups(['review:read', 'book:read'])]
    private ?int $score = null;

    #[ORM\Column(type: 'text')]
    #[Groups(['review:read', 'book:read'])]
    private ?string $message = null;

    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['review:read'])]
    private ?Book $book;

    public function getId(): ?int {
        return $this->id;
    }

    public function getScore(): ?int {
        return $this->score;
    }

    public function setScore(?int $score): static {
        $this->score = $score;

        return $this;
    }

    public function getMessage(): ?string {
        return $this->message;
    }

    public function setMessage(?string $message): static {
        $this->message = $message;

        return $this;
    }

    public function getBook(): ?Book {
        return $this->book;
    }

    public function setBook(?Book $book): static {
        $this->book = $book;

        return $this;
    }
}
