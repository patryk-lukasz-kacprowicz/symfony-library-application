<?php

namespace App\Service;

use App\Dto\BookDTO;
use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class BookService {
    protected EntityManagerInterface $entityManager;
    protected BookRepository $bookRepository;
    protected AuthorRepository $authorRepository;

    public function __construct(EntityManagerInterface $entityManager, BookRepository $bookRepository, AuthorRepository $authorRepository) {
        $this->entityManager = $entityManager;
        $this->bookRepository = $bookRepository;
        $this->authorRepository = $authorRepository;
    }

    public function store(BookDTO $bookDTO): Book | string {
        try {
            $author = $this->authorRepository->find($bookDTO->authorId);

            if (!$author) {
                throw new NotFoundHttpException('Author not found');
            }

            $book = new Book();
            $book->setVisible($bookDTO->visible);
            $book->setAuthor($author);
            $book->setName($bookDTO->name);
            $book->setAmount($bookDTO->amount);
            $book->setPrice($bookDTO->price);
            $book->setCurrency($bookDTO->currency);

            $this->entityManager->persist($book);
            $this->entityManager->flush();

            return $book;
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }
    }

    public function update(BookDTO $bookDTO, int $id): Book | string {
        try {
            $book = $this->bookRepository->find($id);
            $author = $this->authorRepository->find($bookDTO->authorId);

            if (!$book || !$author) {
                throw new NotFoundHttpException("Book or Author not found");
            }

            $book->setVisible($bookDTO->visible);
            $book->setAuthor($author);
            $book->setName($bookDTO->name);
            $book->setAmount($bookDTO->amount);
            $book->setPrice($bookDTO->price);
            $book->setCurrency($bookDTO->currency);

            $this->entityManager->flush();

            return $book;
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }
    }

    public function destroy(int $id): bool | string {
        try {
            $book = $this->bookRepository->find($id);

            if (!$book) {
                throw new NotFoundHttpException("Book not found");
            }

            $this->entityManager->remove($book);
            $this->entityManager->flush();

            return true;
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }
    }
}
