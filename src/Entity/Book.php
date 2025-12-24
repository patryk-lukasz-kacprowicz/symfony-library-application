<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['book:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['book:read'])]
    private ?bool $visible = null;

    #[ORM\Column(length: 255)]
    #[Groups(['book:read'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['book:read'])]
    private ?int $amount = null;

    #[ORM\Column]
    #[Groups(['book:read'])]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    #[Groups(['book:read'])]
    private ?string $currency = null;

    #[ORM\ManyToOne(targetEntity: Author::class, inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['book:read'])]
    private ?Author $author = null;

    public function getId(): ?int {
        return $this->id;
    }

    public function isVisible(): ?bool {
        return $this->visible;
    }

    public function setVisible(bool $visible): static {
        $this->visible = $visible;

        return $this;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): static {
        $this->name = $name;

        return $this;
    }

    public function getAmount(): ?int {
        return $this->amount;
    }

    public function setAmount(int $amount): static {
        $this->amount = $amount;

        return $this;
    }

    public function getPrice(): ?float {
        return $this->price;
    }

    public function setPrice(float $price): static {
        $this->price = $price;

        return $this;
    }

    public function getCurrency(): ?string {
        return $this->currency;
    }

    public function setCurrency(string $currency): static {
        $this->currency = $currency;

        return $this;
    }

    public function getAuthor(): ?Author {
        return $this->author;
    }

    public function setAuthor(?Author $author): static {
        $this->author = $author;

        return $this;
    }
}
