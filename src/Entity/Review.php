<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use http\Exception\InvalidArgumentException;
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
    private ?int $score;

    #[ORM\Column(type: 'text')]
    #[Groups(['review:read', 'book:read'])]
    private ?string $message;

    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['review:read'])]
    private ?Book $book;

    public function __construct(
        Book $book,
        float $score,
        string $message
    ) {
        $this->validateScore($score);

        $this->book = $book;
        $this->score = $score;
        $this->message = $message;
    }

    public function update(
        float $newScore,
        string $newMessage,
    ): void {
        $this->validateScore($newScore);

        $this->score = $newScore;
        $this->message = $newMessage;
    }

    public function validateScore(float $score): void {
        if ($score < 0 || $score > 10) {
            throw new InvalidArgumentException('Score must be between 0 and 10');
        }
    }

    public function getId(): ?int { return $this->id; }
    public function getScore(): ?int { return $this->score; }
    public function getMessage(): ?string { return $this->message; }
    public function getBook(): ?Book { return $this->book; }
}
