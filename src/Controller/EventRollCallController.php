<?php

namespace App\Controller;

use App\Entity\EventRollCall;
use App\Form\EventRollCallType;
use App\Repository\EventRollCallRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[Route('/event/roll/call')]
final class EventRollCallController extends AbstractController
{
    #[Route(name: 'app_event_roll_call_index', methods: ['GET'])]
    public function index(EventRollCallRepository $eventRollCallRepository): Response
    {
        return $this->render('event_roll_call/index.html.twig', [
            'event_roll_calls' => $eventRollCallRepository->findAll(),
        ]);
    }

    private $authorizationChecker;

    // Tiêm AuthorizationChecker vào controller
    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    #[Route('/new', name: 'app_event_roll_call_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Kiểm tra quyền truy cập: chỉ bí thư (ROLE_SECRETARY) và admin (ROLE_ADMIN) mới được tạo điểm danh
        if (!$this->authorizationChecker->isGranted('ROLE_ADMIN') && !$this->authorizationChecker->isGranted('ROLE_SECRETARY')) {
            throw $this->createAccessDeniedException('Bạn không có quyền tạo điểm danh.');
        }

        $eventRollCall = new EventRollCall();
        $form = $this->createForm(EventRollCallType::class, $eventRollCall);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($eventRollCall);
            $entityManager->flush();

            $this->addFlash('success', 'Thêm điểm danh thành công!');

            return $this->redirectToRoute('app_event_roll_call_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event_roll_call/new.html.twig', [
            'event_roll_call' => $eventRollCall,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_roll_call_show', methods: ['GET'])]
    public function show(EventRollCall $eventRollCall): Response
    {
        return $this->render('event_roll_call/show.html.twig', [
            'event_roll_call' => $eventRollCall,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_event_roll_call_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EventRollCall $eventRollCall, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventRollCallType::class, $eventRollCall);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Cập nhật điểm danh thành công!');

            return $this->redirectToRoute('app_event_roll_call_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event_roll_call/edit.html.twig', [
            'event_roll_call' => $eventRollCall,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_roll_call_delete', methods: ['POST'])]
    public function delete(Request $request, EventRollCall $eventRollCall, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $eventRollCall->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($eventRollCall);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_roll_call_index', [], Response::HTTP_SEE_OTHER);
    }
}
