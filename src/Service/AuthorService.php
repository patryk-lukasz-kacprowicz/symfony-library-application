<?php

namespace App\Service;

use App\Dto\AuthorDTO;
use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class AuthorService {
    protected EntityManagerInterface $entityManager;
    protected AuthorRepository $authorRepository;

    public function __construct(EntityManagerInterface $entityManager, AuthorRepository $authorRepository) {
        $this->entityManager = $entityManager;
        $this->authorRepository = $authorRepository;
    }

    public function store(AuthorDTO $authorDTO): Author | string {
        try {
            $author = new Author(
                name: $authorDTO->name,
                country: $authorDTO->country
            );

            $this->entityManager->persist($author);
            $this->entityManager->flush();

            return $author;
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }
    }

    public function update(AuthorDTO $authorDTO, int $id): Author | string {
        try {
            $author = $this->authorRepository->find($id);

            if (!$author) {
                throw new NotFoundHttpException('Author not found');
            }

            $author->update(
                newName: $authorDTO->name,
                newCountry: $authorDTO->country
            );

            $this->entityManager->flush();

            return $author;
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }
    }

    public function destroy(int $id): bool | string {
        try {
            $author = $this->authorRepository->find($id);

            if (!$author) {
                throw new NotFoundHttpException('Author not found');
            }

            $this->entityManager->remove($author);
            $this->entityManager->flush();

            return true;
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }
    }
}
