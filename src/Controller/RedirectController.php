<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class RedirectController extends AbstractController
{
    #[Route('/redirect', name: 'app_redirect')]
    public function index(AuthorizationCheckerInterface $authChecker): RedirectResponse
    {
        if ($authChecker->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse('/union/admin/charts');
        }

        if ($authChecker->isGranted('ROLE_SECRETARY')) {
            return new RedirectResponse('/secretary/dashboard');
        }

        return new RedirectResponse('/dashboard');
    }
}
