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
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Books")]
final class BookController extends AbstractController {
    protected BookService  $bookService;

    public function __construct(BookService $bookService) {
        $this->bookService = $bookService;
    }

    #[Route('/api/books', name: 'api.books.index', methods: ['GET'])]
    #[OA\Get(
        path: "/api/books",
        summary: "Display all books",
        tags: ["Books"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Display all books successfully",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "id", type: "integer", example: 1),
                            new OA\Property(property: "visible", type: "boolean", example: true),
                            new OA\Property(property: "name", type: "string", example: "Harry Potter"),
                            new OA\Property(property: "amount", type: "integer", example: 100),
                            new OA\Property(property: "price", type: "number", format: "float", example: 99.99),
                            new OA\Property(property: "currency", type: "string", example: "USD"),
                            new OA\Property(
                                property: "author",
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 1),
                                    new OA\Property(property: "name", type: "string", example: "J.K. Rowling"),
                                    new OA\Property(property: "country", type: "string", example: "UK")
                                ],
                                type: "object"
                            )
                        ]
                    )
                )
            )
        ]
    )]
    public function index(BookRepository $bookRepository): JsonResponse {
        $books = $bookRepository->findAll();

        return $this->json($books, 200, [], ['groups' => ['book:read']]);
    }

    #[Route('/api/book/{id}', name: 'api.books.show', methods: ['GET'])]
    #[OA\Get(
        path: "/api/book/{id}",
        summary: "Display book data selected by ID",
        tags: ["Books"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "The ID of the book",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Display book data by ID successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "visible", type: "boolean", example: true),
                        new OA\Property(property: "name", type: "string", example: "Harry Potter"),
                        new OA\Property(property: "amount", type: "integer", example: 100),
                        new OA\Property(property: "price", type: "number", format: "float", example: 99.99),
                        new OA\Property(property: "currency", type: "string", example: "USD"),
                        new OA\Property(
                            property: "author",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "name", type: "string", example: "J.K. Rowling"),
                                new OA\Property(property: "country", type: "string", example: "UK")
                            ],
                            type: "object"
                        )
                    ],
                    type: "object"
                )
            )
        ]
    )]
    public function show(BookRepository $bookRepository, int $id): JsonResponse {
        $book = $bookRepository->find($id);

        return $this->json($book, 200, [], ['groups' => ['book:read']]);
    }

    #[Route('/api/books', name: 'api.books.store', methods: ['POST'])]
    #[OA\Post(
        path: "/api/books",
        summary: "Create a new book",
        requestBody: new OA\RequestBody(
            description: "Book data to be saved",
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/BookDTO")
        ),
        tags: ["Books"],
        responses: [
            new OA\Response(
                response: 201,
                description: "Book created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "visible", type: "boolean", example: true),
                        new OA\Property(property: "name", type: "string", example: "Harry Potter"),
                        new OA\Property(property: "amount", type: "integer", example: 100),
                        new OA\Property(property: "price", type: "number", format: "float", example: 99.99),
                        new OA\Property(property: "currency", type: "string", example: "USD"),
                        new OA\Property(
                            property: "author",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "name", type: "string", example: "J.K. Rowling"),
                                new OA\Property(property: "country", type: "string", example: "UK")
                            ],
                            type: "object"
                        )
                    ],
                    type: "object"
                )
            ),
            new OA\Response(
                response: 400,
                description: "Validation error or business logic error",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "error"),
                        new OA\Property(property: "message", type: "string", example: "Author not found.")
                    ],
                    type: "object"
                )
            )
        ]
    )]
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
    #[OA\Put(
        path: "/api/book/{id}",
        summary: "Update book data",
        requestBody: new OA\RequestBody(
            description: "Book data to update",
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/BookDTO")
        ),
        tags: ["Books"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "The ID of the book",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Book updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "visible", type: "boolean", example: true),
                        new OA\Property(property: "name", type: "string", example: "Harry Potter"),
                        new OA\Property(property: "amount", type: "integer", example: 100),
                        new OA\Property(property: "price", type: "number", format: "float", example: 99.99),
                        new OA\Property(property: "currency", type: "string", example: "USD"),
                        new OA\Property(
                            property: "author",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "name", type: "string", example: "J.K. Rowling"),
                                new OA\Property(property: "country", type: "string", example: "UK")
                            ],
                            type: "object"
                        )
                    ],
                    type: "object"
                )
            ),
            new OA\Response(
                response: 400,
                description: "Validation error or business logic error",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "error"),
                        new OA\Property(property: "message", type: "string", example: "Author not found.")
                    ],
                    type: "object"
                )
            )
        ]
    )]
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
    #[OA\Delete(
        path: "/api/book/{id}",
        summary: "Destroy a book",
        tags: ["Books"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "The ID of the book",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 201,
                description: "Book deleted successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Book with ID {id} deleted successfully"),
                    ],
                    type: "object"
                )
            ),
            new OA\Response(
                response: 400,
                description: "Validation error or business logic error",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "error"),
                        new OA\Property(property: "message", type: "string", example: "Author not found.")
                    ],
                    type: "object"
                )
            )
        ]
    )]
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
