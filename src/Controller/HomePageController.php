<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomePageController extends AbstractController {
    #[Route('/', name: 'home-page')]
    public function index(): Response {
        return $this->render('swagger/swagger.html.twig');
    }
}
