<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Account;
use App\Form\AccountType;
use Psr\Log\LoggerInterface;
use App\Service\MailService;
use App\Form\ResetPasswordType;
use App\Form\ForgotPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class AuthController extends AbstractController
{
    // ĐĂNG NHẬP
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Nếu người dùng đã đăng nhập, chuyển hướng theo role
        if ($this->getUser()) {
            return $this->redirectToDashboard();
        }

        return $this->render('auth/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    // ĐĂNG KÝ
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ): Response {
        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Kiểm tra email hoặc số điện thoại đã tồn tại chưa
            $existingAccount = $entityManager->getRepository(Account::class)->findOneBy([
                'email' => $account->getEmail(),
            ]);
            if ($existingAccount) {
                $this->addFlash('error', 'Email này đã được sử dụng.');
                return $this->render('auth/register.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            $existingPhone = $entityManager->getRepository(Account::class)->findOneBy([
                'phoneNumber' => $account->getPhoneNumber(),
            ]);
            if ($existingPhone) {
                $this->addFlash('error', 'Số điện thoại này đã được sử dụng.');
                return $this->render('auth/register.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            // Mã hóa mật khẩu
            $hashedPassword = $passwordHasher->hashPassword($account, $account->getPassword());
            $account->setPassword($hashedPassword);
            $account->setRoles(['ROLE_USER']);

            try {
                // Lưu Account vào DB
                $entityManager->persist($account);
                $entityManager->flush();

                // Sử dụng logger đã được inject
                $logger->info('Đăng ký thành công: ' . $account->getEmail());

                $this->addFlash('success', 'Đăng ký thành công! Hãy đăng nhập.');
                return $this->render('auth/register.html.twig', [
                    'form' => $form->createView(),
                ]);
            } catch (\Exception $e) {
                $logger->error('Lỗi đăng ký: ' . $e->getMessage());
                $this->addFlash('error', 'Đã xảy ra lỗi khi đăng ký: ' . $e->getMessage());
            }
        }

        return $this->render('auth/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    // ĐĂNG XUẤT
    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }

    // QUÊN MẬT KHẨU
    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function forgotPassword(Request $request, MailService $mailService, EntityManagerInterface $entityManager): Response
    {
        // Tạo form
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();

            // Tìm user theo email
            $account = $entityManager->getRepository(Account::class)->findOneBy(['email' => $email]);

            if ($account) {
                // Tạo token reset
                $resetToken = bin2hex(random_bytes(32));

                // Lưu token và thời hạn vào database
                $account->setResetToken($resetToken);
                $entityManager->flush();

                // Tạo link đặt lại mật khẩu
                $resetLink = $this->generateUrl('app_reset_password', ['token' => $resetToken], UrlGeneratorInterface::ABSOLUTE_URL);

                // Tùy chỉnh nội dung email với font chữ hỗ trợ tiếng Việt
                $emailBody = "
                    <html>
                    <head>
                        <meta charset='UTF-8'>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                line-height: 1.6;
                                color: #333;
                            }
                            a {
                                color: #1a73e8;
                                text-decoration: none;
                                font-weight: bold;
                            }
                            a:hover {
                                text-decoration: underline;
                            }
                        </style>
                    </head>
                    <body>
                        <h2>Đặt lại mật khẩu</h2>
                        <p>Chào bạn,</p>
                        <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn. Vui lòng nhấn vào link dưới đây để đặt lại mật khẩu:</p>
                        <p><a href='$resetLink'>Đặt lại mật khẩu</a></p>
                        <p>Link này sẽ hết hạn sau 1 giờ. Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>
                        <p>Trân trọng,<br>Hệ thống quản lý đoàn viên</p>
                    </body>
                    </html>
                ";

                // Gửi email và kiểm tra kết quả
                if ($mailService->sendEmail($email, 'Đặt lại mật khẩu', $emailBody)) {
                    $this->addFlash('success', 'Email đã được gửi đi. Vui lòng kiểm tra hộp thư đến hoặc thư rác.');
                } else {
                    $this->addFlash('danger', 'Có lỗi khi gửi email. Vui lòng thử lại sau.');
                }
            } else {
                $this->addFlash('danger', 'Không tìm thấy email trong hệ thống.');
            }
        }

        return $this->render('auth/forgot-password.html.twig', ['form' => $form->createView()]);
    }

    // ĐẶT LẠI MẬT KHẨU
    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function resetPassword(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        string $token
    ): Response {
        $account = $entityManager->getRepository(Account::class)->findOneBy(['resetToken' => $token]);

        if (!$account) {
            $this->addFlash('danger', 'Token không hợp lệ hoặc đã hết hạn.');
            return $this->redirectToRoute('app_forgot_password');
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('password')->getData(); // Chỉ cần lấy password một lần

            $hashedPassword = $passwordHasher->hashPassword($account, $newPassword);
            $account->setPassword($hashedPassword);
            $account->setResetToken(null);

            try {
                $entityManager->flush();
                $this->addFlash('success', 'Mật khẩu đã được đặt lại. Bạn có thể đăng nhập ngay.');
                return $this->redirectToRoute('app_login');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Có lỗi khi lưu mật khẩu mới: ' . $e->getMessage());

                $this->container->get('security.token_storage')->setToken($this->container->get('security.authentication.manager')->authenticateToken($account));
            }
        }

        return $this->render('auth/reset-password.html.twig', ['form' => $form->createView()]);
    }


    // ĐỔI MẬT KHẨU
    #[Route('/change-password', name: 'app_change_password')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function changePassword(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        // Lấy người dùng hiện tại (phải là entity Account)
        /** @var Account $user */
        $user = $this->getUser();

        if (!$user instanceof Account) {
            throw $this->createAccessDeniedException('Không tìm thấy người dùng');
        }

        // Tạo form
        $form = $this->createFormBuilder()
            ->add('oldPassword', PasswordType::class, ['label' => 'Mật khẩu cũ'])
            ->add('newPassword', PasswordType::class, ['label' => 'Mật khẩu mới'])
            ->add('confirmPassword', PasswordType::class, ['label' => 'Xác nhận mật khẩu mới'])
            ->add('save', SubmitType::class, ['label' => 'Đổi mật khẩu'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Kiểm tra mật khẩu mới có đủ 8 ký tự không
            if (strlen($data['newPassword']) < 8) {
                $this->addFlash('danger', 'Mật khẩu mới phải có ít nhất 8 ký tự.');
                return $this->redirectToRoute('app_change_password'); // Quay lại trang đổi mật khẩu
            }

            // Kiểm tra mật khẩu cũ
            if (!$passwordHasher->isPasswordValid($user, $data['oldPassword'])) {
                $this->addFlash('danger', 'Mật khẩu cũ không đúng.');
                return $this->redirectToRoute('app_change_password');
            } elseif ($data['newPassword'] !== $data['confirmPassword']) {
                $this->addFlash('danger', 'Mật khẩu mới không khớp.');
                return $this->redirectToRoute('app_change_password');
            } else {
                try {
                    // Mã hóa mật khẩu mới và lưu vào database
                    $hashedPassword = $passwordHasher->hashPassword($user, $data['newPassword']);
                    $user->setPassword($hashedPassword);

                    $entityManager->persist($user);
                    $entityManager->flush();

                    $this->addFlash('success', 'Mật khẩu đã được thay đổi thành công.');
                    return $this->redirectToRoute('app_dashboard');
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Có lỗi khi đổi mật khẩu: ' . $e->getMessage());
                }
            }
        }

        return $this->render('auth/change-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // DASHBOARD
    private function redirectToDashboard(): RedirectResponse
    {
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_SECRETARY')) {
            return $this->redirectToRoute('admin_charts'); // Cùng vào trang admin
        }

        if ($this->isGranted('ROLE_MEMBER')) {
            return $this->redirectToRoute('youth_member'); // Trang dành riêng cho đoàn viên
        }

        return $this->redirectToRoute('app_home'); // Trang mặc định nếu không có role
    }

    #[Route('/union/admin/charts', name: 'admin_charts')]
    public function adminCharts(): Response
    {
        return $this->render('admin/charts.html.twig');
    }
}