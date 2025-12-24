<?php

namespace App\Controller;

use App\Dto\BookDTO;
use App\Entity\Book;
use App\Repository\BookRepository;
use App\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

final class BookController extends AbstractController {
    protected BookService  $bookService;

    public function __construct(BookService $bookService) {
        $this->bookService = $bookService;
    }

    #[Route('/api/books', name: 'api.books.index', methods: ['GET'])]
    public function index(BookRepository $bookRepository): JsonResponse {
        $books = $bookRepository->findAll();

        return $this->json($books, 200, [], ['groups' => ['book:read']]);
    }

    #[Route('/api/book/{id}', name: 'api.books.show', methods: ['GET'])]
    public function show(BookRepository $bookRepository, int $id): JsonResponse {
        $book = $bookRepository->find($id);

        return $this->json($book, 200, [], ['groups' => ['book:read']]);
    }

    #[Route('/api/books', name: 'api.books.store', methods: ['POST'])]
    public function store(#[MapRequestPayload] BookDTO $bookDTO): JsonResponse {
        $result = $this->bookService->store($bookDTO);

        if ($result instanceof Book) {
            return $this->json($result, 201, [], ['groups' => ['book:read']]);
        }

        return new JsonResponse([
            'status' => 'error',
            'message' => $result,
        ], 400);
    }

    #[Route('/api/book/{id}', name: 'api.books.update', methods: ['PUT'])]
    public function update(#[MapRequestPayload] BookDTO $bookDTO, int $id): JsonResponse {
        $result = $this->bookService->update($bookDTO, $id);

        if ($result instanceof Book) {
            return $this->json($result, 200, [], ['groups' => ['book:read']]);
        }

        return new JsonResponse([
            'status' => 'error',
            'message' => $result,
        ], 400);
    }

    #[Route('/api/book/{id}', name: 'api.books.destroy', methods: ['DELETE'])]
    public function destroy(int $id): JsonResponse {
        $result = $this->bookService->destroy($id);

        if ($result === true) {
            return new JsonResponse([
                'status' => 'success',
                'message' => sprintf('Book with ID %s deleted successfully', $id),
            ], 200);
        }

        return new JsonResponse([
            'status' => 'error',
            'message' => $result,
        ]);
    }
}
