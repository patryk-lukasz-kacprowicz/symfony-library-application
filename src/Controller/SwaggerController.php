<?php

namespace App\Controller;

use OpenApi\Generator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    description: "",
    title: "Virtual Library documentation API"
)]
#[OA\Server(
    url: "http://localhost:8000",
    description: "Local server"
)]
final class SwaggerController extends AbstractController {
    #[Route('/', name: 'general')]
    public function index(): Response {
        return $this->render('swagger/swagger.html.twig');
    }

    #[Route('/api/doc.json', name: 'api.documentation', methods: ['GET'])]
    public function generateDocumentation(): JsonResponse {
        $openapi = Generator::scan([
            $this->getParameter('kernel.project_dir') . '/src'
        ]);

        return new JsonResponse($openapi->toJson(), 200, [], true);
    }
}
