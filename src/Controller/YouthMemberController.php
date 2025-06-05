<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class YouthMemberController extends AbstractController
{
    #[Route('/youth/member', name: 'app_youth_member')]
    public function index(): Response
    {
        return $this->render('youth_member/index.html.twig', [
            'controller_name' => 'YouthMemberController',
        ]);
    }
}
