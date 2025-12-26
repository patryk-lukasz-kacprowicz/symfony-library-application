<?php

namespace App\Controller;

use App\Dto\ReviewDTO;
use App\Entity\Review;
use App\Repository\ReviewRepository;
use App\Service\ReviewService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Reviews")]
final class ReviewController extends AbstractController {
    protected ReviewService $reviewService;

    public function __construct(ReviewService $reviewService) {
        $this->reviewService = $reviewService;
    }

    #[Route('/api/reviews', name: 'api.reviews.index', methods: ['GET'])]
    #[OA\Get(
        path: "/api/reviews",
        summary: "Display all reviews",
        tags: ["Reviews"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Display all reviews successfully",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "id", type: "integer", example: 1),
                            new OA\Property(property: "score", type: "float", example: 5.3),
                            new OA\Property(property: "message", type: "string", example: "Harry Potter is awesome!"),
                            new OA\Property(
                                property: "book",
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
                        ]
                    )
                )
            )
        ]
    )]
    public function index(ReviewRepository $reviewRepository): JsonResponse {
        $reviews = $reviewRepository->findAll();

        return $this->json($reviews, 200, [], ['groups' => ['review:read']]);
    }

    #[Route('/api/review/{id}', name: 'api.reviews.show', methods: ['GET'])]
    #[OA\Get(
        path: "/api/review/{id}",
        summary: "Display review data selected by ID",
        tags: ["Reviews"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "The ID of the review",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Display review data by ID successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "score", type: "float", example: 5.3),
                        new OA\Property(property: "message", type: "string", example: "Harry Potter is awesome!"),
                        new OA\Property(
                            property: "book",
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
                    ],
                    type: "object"
                )
            ),

            new OA\Response(
                response: 404,
                description: "Review not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: 'error'),
                        new OA\Property(property: "message", type: "string", example: 'Review not found'),
                    ],
                    type: 'object'
                )
            )
        ]
    )]
    public function show(ReviewRepository $reviewRepository, int $id): JsonResponse {
        try {
            $review = $reviewRepository->find($id);

            if (!$review) {
                throw new NotFoundHttpException('Review not found');
            }

            return $this->json($review, 200, [], ['groups' => ['review:read']]);
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 400);
        }
    }

    #[Route('/api/reviews', name: 'api.reviews.store', methods: ['POST'])]
    #[OA\Post(
        path: "/api/reviews",
        summary: "Create a new review",
        requestBody: new OA\RequestBody(
            description: "Review data to be saved",
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/ReviewDTO")
        ),
        tags: ["Reviews"],
        responses: [
            new OA\Response(
                response: 201,
                description: "Review created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "score", type: "float", example: 5.3),
                        new OA\Property(property: "message", type: "string", example: "Harry Potter is awesome!"),
                        new OA\Property(
                            property: "book",
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
    public function store(#[MapRequestPayload] ReviewDTO $reviewDTO): JsonResponse {
        $result = $this->reviewService->store($reviewDTO);

        if ($result instanceof Review) {
            return $this->json($result, 201, [], ['groups' => ['review:read']]);
        }

        return new JsonResponse([
            'status' => 'error',
            'message' => $result,
        ], 400);
    }

    #[Route('/api/review/{id}', name: 'api.reviews.update', methods: ['PUT'])]
    #[OA\Put(
        path: "/api/review/{id}",
        summary: "Update review data",
        requestBody: new OA\RequestBody(
            description: "Review data to update",
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/ReviewDTO")
        ),
        tags: ["Reviews"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "The ID of the review",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Review updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "score", type: "float", example: 5.3),
                        new OA\Property(property: "message", type: "string", example: "Harry Potter is awesome!"),
                        new OA\Property(
                            property: "book",
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
    public function update(#[MapRequestPayload] ReviewDTO $reviewDTO, int $id): JsonResponse {
        $result = $this->reviewService->update($reviewDTO, $id);

        if ($result instanceof Review) {
            return $this->json($result, 201, [], ['groups' => ['review:read']]);
        }

        return new JsonResponse([
            'status' => 'error',
            'message' => $result,
        ]);
    }

    #[Route('/api/review/{id}', name: 'api.reviews.destroy', methods: ['DELETE'])]
    #[OA\Delete(
        path: "/api/review/{id}",
        summary: "Destroy a review",
        tags: ["Reviews"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "The ID of the review",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 201,
                description: "Review deleted successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Review with ID {id} deleted successfully"),
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
                        new OA\Property(property: "message", type: "string", example: "Review not found.")
                    ],
                    type: "object"
                )
            )
        ]
    )]
    public function destroy(int $id): JsonResponse {
        $result = $this->reviewService->destroy($id);

        if ($result === true) {
            return new JsonResponse([
                'status' => 'success',
                'message' => sprintf('Review with ID %s deleted successfully', $id),
            ], 200);
        }

        return new JsonResponse([
            'status' => 'error',
            'message' => $result,
        ]);
    }
}
