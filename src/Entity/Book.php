<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['book:read', 'review:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['book:read', 'review:read'])]
    private ?bool $visible;

    #[ORM\Column(length: 255)]
    #[Groups(['book:read', 'review:read'])]
    private ?string $name;

    #[ORM\Column]
    #[Groups(['book:read', 'review:read'])]
    private ?int $amount;

    #[ORM\Column]
    #[Groups(['book:read', 'review:read'])]
    private ?float $price;

    #[ORM\Column(length: 255)]
    #[Groups(['book:read', 'review:read'])]
    private ?string $currency;

    #[ORM\ManyToOne(targetEntity: Author::class, inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['book:read', 'review:read'])]
    private ?Author $author;

    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'book', cascade: ['persist', 'remove'])]
    #[Groups(['book:read'])]
    private Collection $reviews;

    public function __construct(
        Author $author,
        string $name,
        int $amount,
        float $price,
        string $currency,
        bool $visible = false
    ) {
        $this->reviews = new ArrayCollection();

        $this->author = $author;
        $this->name = $name;
        $this->amount = $amount;
        $this->price = $price;
        $this->currency = $currency;
        $this->visible = $visible;
    }

    public function update(
        Author $newAuthor,
        string $newName,
        int $newAmount,
        float $newPrice,
        string $newCurrency,
        bool $newVisible = false
    ): void {
        $this->author = $newAuthor;
        $this->name = $newName;
        $this->amount = $newAmount;
        $this->price = $newPrice;
        $this->currency = $newCurrency;
        $this->visible = $newVisible;
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function getAmount(): ?int { return $this->amount; }
    public function getPrice(): ?float { return $this->price; }
    public function getCurrency(): ?string { return $this->currency; }
    public function getAuthor(): ?Author { return $this->author; }
    public function getReviews(): Collection { return $this->reviews; }
}
