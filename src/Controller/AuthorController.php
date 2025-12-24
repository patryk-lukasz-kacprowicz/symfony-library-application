<?php

namespace App\Controller;

use App\Dto\AuthorDTO;
use App\Entity\Author;
use App\Repository\AuthorRepository;
use App\Service\AuthorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class AuthorController extends AbstractController {
    protected AuthorRepository $authorRepository;
    protected AuthorService $authorService;

    public function __construct(AuthorRepository $authorRepository, AuthorService $authorService) {
        $this->authorRepository = $authorRepository;
        $this->authorService = $authorService;
    }

    #[Route('/api/authors', name: 'api.authors.index', methods: ['GET'])]
    public function index(): JsonResponse {
        $authors = $this->authorRepository->findAll();

        return $this->json($authors, 200, [], ['groups' => ['book:read']]);
    }

    #[Route('/api/author/{id}', name: 'api.authors.show', methods: ['GET'])]
    public function show(int $id): JsonResponse {
        $author = $this->authorRepository->find($id);

        return $this->json($author, 200, [], ['groups' => ['book:read']]);
    }

    #[Route('/api/authors', name: 'api.authors.store', methods: ['POST'])]
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
