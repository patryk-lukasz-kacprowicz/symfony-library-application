<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['book:read', 'review:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['book:read', 'review:read'])]
    private ?string $name;

    #[ORM\Column(length: 255)]
    #[Groups(['book:read', 'review:read'])]
    private ?string $country;

    #[ORM\OneToMany(targetEntity: Book::class, mappedBy: 'author')]
    private Collection $books;

    public function __construct(
        string $name,
        string $country
    ) {
        $this->books = new ArrayCollection();

        $this->name = $name;
        $this->country = $country;
    }

    public function update(
        string $newName,
        string $newCountry,
    ): void {
        $this->name = $newName;
        $this->country = $newCountry;
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function getCountry(): ?string { return $this->country; }
    public function getBooks(): Collection { return $this->books; }
}
