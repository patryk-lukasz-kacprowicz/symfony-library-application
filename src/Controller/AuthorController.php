<?php

namespace App\Controller;

use App\Dto\AuthorDTO;
use App\Entity\Author;
use App\Repository\AuthorRepository;
use App\Service\AuthorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Throwable;

#[OA\Tag(name: "Authors")]
final class AuthorController extends AbstractController {
    protected AuthorRepository $authorRepository;
    protected AuthorService $authorService;

    public function __construct(AuthorRepository $authorRepository, AuthorService $authorService) {
        $this->authorRepository = $authorRepository;
        $this->authorService = $authorService;
    }

    #[Route('/api/authors', name: 'api.authors.index', methods: ['GET'])]
    #[OA\Get(
        path: "/api/authors",
        summary: "Display all authors",
        tags: ["Authors"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Display all authors successfully",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "id", type: "integer", example: 1),
                            new OA\Property(property: "name", type: "string", example: "J.K. Rowling"),
                            new OA\Property(property: "country", type: "string", example: "UK"),
                        ]
                    )
                )
            )
        ]
    )]
    public function index(): JsonResponse {
        $authors = $this->authorRepository->findAll();

        return $this->json($authors, 200, [], ['groups' => ['book:read']]);
    }

    #[Route('/api/author/{id}', name: 'api.authors.show', methods: ['GET'])]
    #[OA\Get(
        path: "/api/author/{id}",
        summary: "Display author data selected by ID",
        tags: ["Authors"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "The ID of the author",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Display author data by ID successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "name", type: "string", example: "J.K. Rowling"),
                        new OA\Property(property: "country", type: "string", example: "UK"),
                    ],
                    type: "object"
                )
            )
        ]
    )]
    public function show(int $id): JsonResponse {
        try {
            $author = $this->authorRepository->find($id);

            if (!$author) {
                throw new NotFoundHttpException('Author not found');
            }

            return $this->json($author, 200, [], ['groups' => ['book:read']]);
        } catch (Throwable $exception) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 400);
        }

    }

    #[Route('/api/authors', name: 'api.authors.store', methods: ['POST'])]
    #[OA\Post(
        path: "/api/authors",
        summary: "Create a new author",
        requestBody: new OA\RequestBody(
            description: "Author data to be saved",
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/AuthorDTO")
        ),
        tags: ["Authors"],
        responses: [
            new OA\Response(
                response: 201,
                description: "Author created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "name", type: "string", example: "J.K. Rowling"),
                        new OA\Property(property: "country", type: "string", example: "UK"),
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
    public function store(#[MapRequestPayload] AuthorDTO $authorDTO): JsonResponse {
        $result = $this->authorService->store($authorDTO);

        if ($result instanceof Author) {
            return $this->json($result, 201, [], ['groups' => ['book:read']]);
        }

        return new JsonResponse([
            'status' => 'error',
            'message' => $result,
        ], 400);
    }

    #[Route('/api/author/{id}', name: 'api.authors.update', methods: ['PUT'])]
    #[OA\Put(
        path: "/api/author/{id}",
        summary: "Update author data",
        requestBody: new OA\RequestBody(
            description: "Author data to update",
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/AuthorDTO")
        ),
        tags: ["Authors"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "The ID of the author",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Author updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "name", type: "string", example: "J.K. Rowling"),
                        new OA\Property(property: "country", type: "string", example: "UK"),
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
    public function update(#[MapRequestPayload] AuthorDTO $authorDTO, int $id): JsonResponse {
        $result = $this->authorService->update($authorDTO, $id);

        if ($result instanceof Author) {
            return $this->json($result, 200, [], ['groups' => ['book:read']]);
        }

        return new JsonResponse([
            'status' => 'error',
            'message' => $result,
        ], 400);
    }

    #[Route('/api/author/{id}', name: 'api.authors.destroy', methods: ['DELETE'])]
    #[OA\Delete(
        path: "/api/author/{id}",
        summary: "Destroy a author",
        tags: ["Authors"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "The ID of the author",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 201,
                description: "Author deleted successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Author with ID {id} deleted successfully"),
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
        $result = $this->authorService->destroy($id);

        if ($result === true) {
            return new JsonResponse([
                'status' => 'success',
                'message' => sprintf('Author with ID %s deleted successfully', $id),
            ], 200);
        }

        return new JsonResponse([
            'status' => 'error',
            'message' => $result,
        ]);
    }
}
