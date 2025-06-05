<?php

namespace App\Controller;

use App\Entity\YouthUnionSecretary;
use App\Form\YouthUnionSecretaryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Repository\UserRepository;

final class YouthUnionSecretaryController extends AbstractController
{
    #[Route('/youth/union/secretary', name: 'app_youth_union_secretary')]
    public function index(): Response
    {
        return $this->render('youth_union_secretary/index.html.twig', [
            'controller_name' => 'YouthUnionSecretaryController',
        ]);
    }

    #[Route('/youth/union/secretary/new', name: 'app_youth_union_secretary_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
{
    $secretary = new YouthUnionSecretary();
    $form = $this->createForm(YouthUnionSecretaryType::class, $secretary);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Lấy Union Admin ID từ request (giả sử form có trường này)
        $unionAdminId = $request->request->get('union_admin_id'); 

        if ($unionAdminId) {
            $unionAdmin = $userRepository->find($unionAdminId);

            if (!$unionAdmin) {
                $this->addFlash('error', 'Cán bộ đoàn không tồn tại.');
                return $this->redirectToRoute('app_youth_union_secretary_new');
            }

            $secretary->setUnionAdmin($unionAdmin);
        }

        $entityManager->persist($secretary);
        $entityManager->flush();

        $this->addFlash('success', 'YouthUnionSecretary đã được tạo thành công!');
        return $this->redirectToRoute('app_youth_union_secretary');
    }

    return $this->render('youth_union_secretary/new.html.twig', [
        'form' => $form->createView(),
    ]);
}
}