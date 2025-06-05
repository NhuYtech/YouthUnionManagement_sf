<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TablesController extends AbstractController
{
    #[Route('/tables', name: 'app_tables')]
    public function index(): Response
    {
        return $this->render('tables/index.html.twig', [
            'controller_name' => 'TablesController',
        ]);
    }
}
