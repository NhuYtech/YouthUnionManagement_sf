<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ManagementController extends AbstractController
{
    #[Route('/management', name: 'app_management')]
    public function index(): Response
    {
        return $this->render('management/index.html.twig', [
            'controller_name' => 'ManagementController',
        ]);
    }
}
