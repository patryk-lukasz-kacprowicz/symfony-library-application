<?php

namespace App\Service;

use App\Dto\ReviewDTO;
use App\Entity\Review;
use App\Repository\BookRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ReviewService {
    protected EntityManagerInterface $entityManager;
    protected ReviewRepository $reviewRepository;
    protected BookRepository $bookRepository;

    public function __construct(EntityManagerInterface $entityManager, ReviewRepository $reviewRepository, BookRepository $bookRepository) {
        $this->entityManager = $entityManager;
        $this->reviewRepository = $reviewRepository;
        $this->bookRepository = $bookRepository;
    }

    public function store(ReviewDTO $reviewDTO): Review | string {
        try {
            $book = $this->bookRepository->find($reviewDTO->bookId);

            if (!$book) {
                throw new NotFoundHttpException('Book not found');
            }

            $review = new Review();
            $review->setBook($book);
            $review->setScore($reviewDTO->score);
            $review->setMessage($reviewDTO->message);

            $this->entityManager->persist($review);
            $this->entityManager->flush();

            return $review;
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }
    }

    public function update(ReviewDTO $reviewDTO, int $id): Review | string {
        try {
            $review = $this->reviewRepository->find($id);

            if (!$review) {
                throw new NotFoundHttpException('Review not found');
            }

            $review->setScore($reviewDTO->score);
            $review->setMessage($reviewDTO->message);

            $this->entityManager->flush();

            return $review;
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }
    }

    public function destroy(int $id): bool | string {
        try {
            $review = $this->reviewRepository->find($id);

            if (!$review) {
                throw new NotFoundHttpException('Review not found');
            }

            $this->entityManager->remove($review);
            $this->entityManager->flush();

            return true;
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }
    }
}
