<?php

namespace App\Controller;

use App\Entity\InstructionDocument;
use App\Form\InstructionDocumentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\InstructionDocumentRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/instruction/document')]
final class InstructionDocumentController extends AbstractController
{
    #[Route(name: 'app_instruction_document_index', methods: ['GET'])]
    public function index(InstructionDocumentRepository $instructionDocumentRepository): Response
    {
        return $this->render('instruction_document/index.html.twig', [
            'instruction_documents' => $instructionDocumentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_instruction_document_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $instructionDocument = new InstructionDocument();
        $form = $this->createForm(InstructionDocumentType::class, $instructionDocument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Xử lý file PDF
            /** @var UploadedFile $pdfFile */
            $pdfFile = $form->get('pdfFile')->getData();

            if ($pdfFile) {
                $originalFilename = pathinfo($pdfFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [\u0800-\u4e00] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $pdfFile->guessExtension();

                try {
                    $pdfFile->move(
                        $this->getParameter('pdfs_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'File không thể tải lên.');
                    return $this->redirectToRoute('app_instruction_document_new');
                }

                // Lưu tên file vào trong entity
                $instructionDocument->setAttachment($newFilename);
            }

            $entityManager->persist($instructionDocument);
            $entityManager->flush();

            $this->addFlash('success', 'Thêm văn bản chỉ đạo thành công!');

            return $this->redirectToRoute('app_instruction_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('instruction_document/new.html.twig', [
            'instruction_document' => $instructionDocument,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_instruction_document_show', methods: ['GET'])]
    public function show(InstructionDocument $instructionDocument): Response
    {
        return $this->render('instruction_document/show.html.twig', [
            'instruction_document' => $instructionDocument,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_instruction_document_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, InstructionDocument $instructionDocument, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InstructionDocumentType::class, $instructionDocument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Cập nhật văn bản chỉ đạo thành công!');

            return $this->redirectToRoute('app_instruction_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('instruction_document/edit.html.twig', [
            'instruction_document' => $instructionDocument,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_instruction_document_delete', methods: ['POST'])]
    public function delete(Request $request, InstructionDocument $instructionDocument, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $instructionDocument->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($instructionDocument);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_instruction_document_index', [], Response::HTTP_SEE_OTHER);
    }
}
