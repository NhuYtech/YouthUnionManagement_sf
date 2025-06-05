<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UnionAdminController extends AbstractController
{
    #[Route('/union/admin', name: 'app_union_admin')]
    public function index(): Response
    {
        return $this->render('union_admin/index.html.twig', [
            'controller_name' => 'UnionAdminController',
        ]);
    }

    #[Route('/union/admin/charts', name: 'admin_charts')]
    public function charts(): Response
    {
        return $this->render('dashbroad/charts.html.twig');
    }

    #[Route('/union/admin/tables', name: 'admin_tables')]
    public function tables(): Response
    {
        return $this->render('dashbroad/tables.html.twig');
    }
}
