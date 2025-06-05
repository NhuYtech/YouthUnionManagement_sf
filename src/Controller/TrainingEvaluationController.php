<?php

namespace App\Controller;

use App\Entity\TrainingEvaluation;
use App\Form\TrainingEvaluationType;
use App\Repository\TrainingEvaluationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;


#[Route('/training/evaluation')]
final class TrainingEvaluationController extends AbstractController
{
    #[Route(name: 'app_training_evaluation_index', methods: ['GET'])]
    public function index(Request $request, TrainingEvaluationRepository $trainingEvaluationRepository): Response
    {
        /** @var \App\Entity\Account $account */
        $account = $this->getUser();

        if (in_array('ROLE_SECRETARY', $account->getRoles())) {
            $unitName = $account->getFullName();
            $trainingEvaluations = $trainingEvaluationRepository->findByUserUnitName($unitName);
        } else {
            $unitName = $request->query->get('unitName');
            $trainingEvaluations = $unitName
                ? $trainingEvaluationRepository->findByUserUnitName($unitName)
                : $trainingEvaluationRepository->findAll();
        }

        return $this->render('training_evaluation/index.html.twig', [
            'training_evaluations' => $trainingEvaluations,
        ]);
    }


    #[Route('/new', name: 'app_training_evaluation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trainingEvaluation = new TrainingEvaluation();
        $form = $this->createForm(TrainingEvaluationType::class, $trainingEvaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($trainingEvaluation);
            $entityManager->flush();

            $this->addFlash('success', 'Thêm đánh giá, xếp loại thành công!');

            return $this->redirectToRoute('app_training_evaluation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('training_evaluation/new.html.twig', [
            'training_evaluation' => $trainingEvaluation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_training_evaluation_show', methods: ['GET'])]
    public function show(TrainingEvaluation $trainingEvaluation): Response
    {
        return $this->render('training_evaluation/show.html.twig', [
            'training_evaluation' => $trainingEvaluation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_training_evaluation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TrainingEvaluation $trainingEvaluation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TrainingEvaluationType::class, $trainingEvaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Cập nhật đánh giá, xếp loại thành công!');

            return $this->redirectToRoute('app_training_evaluation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('training_evaluation/edit.html.twig', [
            'training_evaluation' => $trainingEvaluation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_training_evaluation_delete', methods: ['POST'])]
    public function delete(Request $request, TrainingEvaluation $trainingEvaluation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $trainingEvaluation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($trainingEvaluation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_training_evaluation_index', [], Response::HTTP_SEE_OTHER);
    }
}
