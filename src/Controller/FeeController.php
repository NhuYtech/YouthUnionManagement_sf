<?php

namespace App\Controller;

use App\Entity\Fee;
use App\Form\FeeType;
use App\Repository\FeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/fee')]
final class FeeController extends AbstractController
{

    #[Route(name: 'app_fee_index', methods: ['GET'])]
    public function index(FeeRepository $feeRepository): Response
    {
        $user = $this->getUser();

        // Nếu là đoàn viên → chỉ lấy phí của đơn vị mình
        if ($this->isGranted('ROLE_MEMBER')) {
            $fees = $feeRepository->findBy([
                'youthUnionSecretary' => $user
            ]);
        } else {
            // Nếu là cán bộ hoặc admin → xem tất cả
            $fees = $feeRepository->findAll();
        }

        return $this->render('fee/index.html.twig', [
            'fees' => $fees,
        ]);
    }


    #[Route('/new', name: 'app_fee_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $fee = new Fee();
        $form = $this->createForm(FeeType::class, $fee, [
            'is_admin' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $memberCount = $fee->getMemberCount();
                $amount = round(($memberCount * 12 * 2) / 3);
                $fee->setAmount($amount);
                $fee->setStatus('submitted');

                $entityManager->persist($fee);
                $entityManager->flush();

                $this->addFlash('success', 'Thêm đoàn phí thành công.');
                return $this->redirectToRoute('app_fee_index');
            } else {
                foreach ($form->getErrors(true) as $error) {
                    if (str_contains($error->getMessage(), 'CSRF')) {
                        $this->addFlash('error', 'Token bảo mật đã hết hạn. Vui lòng thử lại.');
                    } else {
                        $this->addFlash('error', 'Có lỗi trong dữ liệu: ' . $error->getMessage());
                    }
                }
            }
        }

        return $this->render('fee/new.html.twig', [
            'fee' => $fee,
            'form' => $form->createView(),
        ]);
    }



    #[Route('/{id}', name: 'app_fee_show', methods: ['GET'])]
    public function show(Fee $fee): Response
    {
        return $this->render('fee/show.html.twig', [
            'fee' => $fee,
            'unitName' => $fee->getUser()?->getUnitName(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fee_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fee $fee, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FeeType::class, $fee, [
            'is_admin' => $this->isGranted('ROLE_ADMIN'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $memberCount = $fee->getMemberCount();
            $amount = round(($memberCount * 12 * 2) / 3);
            $fee->setAmount($amount);

            $entityManager->flush();
            return $this->redirectToRoute('app_fee_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fee/edit.html.twig', [
            'fee' => $fee,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_fee_delete', methods: ['POST'])]
    public function delete(Request $request, Fee $fee, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $fee->getId(), $request->request->get('_token'))) {
            $entityManager->remove($fee);
            $entityManager->flush();
        } else {
            $this->addFlash('error', 'Token CSRF không hợp lệ.');
        }

        return $this->redirectToRoute('app_fee_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/update-status', name: 'app_fee_update_status', methods: ['POST'])]
    public function updateStatus(Request $request, Fee $fee, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('update_status' . $fee->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF không hợp lệ.');
        }

        $newStatus = $request->request->get('status');

        if (!in_array($newStatus, ['approved', 'rejected'])) {
            throw $this->createNotFoundException('Trạng thái không hợp lệ.');
        }

        $fee->setStatus($newStatus);
        $entityManager->flush();

        $this->addFlash('success', 'Cập nhật trạng thái thành công.');
        return $this->redirectToRoute('app_fee_index');
    }
}


