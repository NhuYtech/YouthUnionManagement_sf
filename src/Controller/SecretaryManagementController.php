<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class SecretaryManagementController extends AbstractController
{
    #[Route('/secretary/management', name: 'app_secretary_management')]
    public function index(): Response
    {
        return $this->render('secretary_management/index.html.twig', [
            'controller_name' => 'SecretaryManagementController',
        ]);
    }

    #[Route('/secretary/management/create', name: 'create_secretary', methods: ['GET', 'POST'])]
    public function createSecretary(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            // kiểm tra dữ liệu nhập vào
            if (empty($data['fullName']) || empty($data['phoneNumber']) || empty($data['password'])) {
                $this->addFlash('error', 'Vui lòng nhập đầy đủ thông tin!');
                return $this->redirectToRoute('create_secretary');
            }

            // kiểm tra định dạng sđt (10 số và bắt đầu bằng số 0)
            if (!preg_match('/^0[0-9]{9}$/', $data['phoneNumber'])) {
                $this->addFlash('error', 'Số điện thoại không hợp lệ! (Bắt đầu bằng 0, đủ 10 số)');
                return $this->redirectToRoute('create_secretary');
            }

            // kiểm tra sđt đã tồn tại chưa
            $existingAccount = $entityManager->getRepository(Account::class)->findOneBy(['phoneNumber' => $data['phoneNumber']]);
            if ($existingAccount) {
                $this->addFlash('error', 'Số điện thoại này đã được sử dụng!');
                return $this->redirectToRoute('create_secretary');
            }

            // tạo tài khoản
            $account = new Account();
            $account->setFullName($data['fullName']);
            $account->setPhoneNumber($data['phoneNumber']);

            // mã hóa mk
            $hashedPassword = $passwordHasher->hashPassword($account, $data['password']);
            $account->setPassword($hashedPassword);

            // gán quyền cho BT
            $account->setRoles(['ROLE_SECRETARY']);

            // lưu vào DB
            $entityManager->persist($account);
            $entityManager->flush();

            $this->addFlash('success', 'Tạo tài khoản Bí thư thành công!');
            return $this->redirectToRoute('app_secretary_management');
        }

        return $this->render('secretary_management/create_secretary.html.twig');
    }
}
