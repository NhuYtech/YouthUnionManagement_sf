<?php

namespace App\Controller;

use App\Entity\Recognition;
use App\Form\RecognitionType;
use App\Repository\RecognitionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/recognition')]
final class RecognitionController extends AbstractController
{
    #[Route(name: 'app_recognition_index', methods: ['GET'])]
    public function index(Request $request, RecognitionRepository $recognitionRepository): Response
    {
        /** @var \App\Entity\Account $account */
        $account = $this->getUser();

        if (in_array('ROLE_SECRETARY', $account->getRoles())) {

            $unitName = $account->getFullName();

            $recognitions = $recognitionRepository->findByUserUnitName($unitName);
        } else {
            $unitName = $request->query->get('unitName');
            $recognitions = $unitName
                ? $recognitionRepository->findByUserUnitName($unitName)
                : $recognitionRepository->findAll();
        }

        return $this->render('recognition/index.html.twig', [
            'recognitions' => $recognitions,
        ]);
    }


    #[Route('/new', name: 'app_recognition_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $recognition = new Recognition();
        $form = $this->createForm(RecognitionType::class, $recognition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Xử lý upload file PDF
            $pdfFile = $form->get('pdfFile')->getData();
            if ($pdfFile) {
                $originalFilename = pathinfo($pdfFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $pdfFile->guessExtension();

                try {
                    $pdfFile->move(
                        $this->getParameter('pdfs_directory'), // Định nghĩa trong services.yaml
                        $newFilename
                    );
                    $recognition->setFileDecision($newFilename);
                } catch (FileException $e) {
                    // Log lỗi nếu cần
                }
            }

            $entityManager->persist($recognition);
            $entityManager->flush();

            return $this->redirectToRoute('app_recognition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recognition/new.html.twig', [
            'recognition' => $recognition,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_recognition_show', methods: ['GET'])]
    public function show(Recognition $recognition): Response
    {
        return $this->render('recognition/show.html.twig', [
            'recognition' => $recognition,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recognition_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recognition $recognition, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        // Lưu lại giá trị cũ
        $oldPdf = $recognition->getFileDecision();

        $form = $this->createForm(RecognitionType::class, $recognition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Xử lý upload file PDF mới nếu có
            $pdfFile = $form->get('pdfFile')->getData();
            if ($pdfFile) {
                $originalFilename = pathinfo($pdfFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $pdfFile->guessExtension();

                try {
                    $pdfFile->move(
                        $this->getParameter('pdfs_directory'),
                        $newFilename
                    );
                    // Xóa file PDF cũ
                    if ($oldPdf) {
                        @unlink($this->getParameter('pdfs_directory') . '/' . $oldPdf);
                    }
                    $recognition->setFileDecision($newFilename);
                } catch (FileException $e) {
                    // Log lỗi nếu cần
                }
            } else {
                // Nếu không upload file mới, giữ lại file cũ
                $recognition->setFileDecision($oldPdf);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_recognition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recognition/edit.html.twig', [
            'recognition' => $recognition,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_recognition_delete', methods: ['POST'])]
    public function delete(Request $request, Recognition $recognition, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $recognition->getId(), $request->request->get('_token'))) {
            // Xóa file PDF nếu có
            if ($recognition->getFileDecision()) {
                @unlink($this->getParameter('pdfs_directory') . '/' . $recognition->getFileDecision());
            }

            $entityManager->remove($recognition);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recognition_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/status', name: 'app_recognition_update_status', methods: ['POST'])]
    public function updateStatus(Request $request, Recognition $recognition, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('update_status' . $recognition->getId(), $request->request->get('_token'))) {
            $newStatus = $request->request->get('status');
            $recognition->setStatus($newStatus);
            $entityManager->flush();

            $this->addFlash('success', 'Trạng thái đã được cập nhật.');
        }

        return $this->redirectToRoute('app_recognition_index');
    }

}