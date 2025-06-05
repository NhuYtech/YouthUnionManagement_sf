<?php

namespace App\Security;

use App\Repository\AccountRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\HttpFoundation\RequestStack;

class CustomAuthenticator extends AbstractAuthenticator
{
    private AccountRepository $accountRepository;
    private UrlGeneratorInterface $urlGenerator;
    private RequestStack $requestStack;

    public function __construct(AccountRepository $accountRepository, UrlGeneratorInterface $urlGenerator, RequestStack $requestStack)
    {
        $this->accountRepository = $accountRepository;
        $this->urlGenerator = $urlGenerator;
        $this->requestStack = $requestStack;
    }

    /**
     * Xác định khi nào authenticator này được gọi.
     * Chỉ kích hoạt khi request là POST và route là app_login.
     */
    public function supports(Request $request): ?bool
    {
        return $request->isMethod('POST') && $request->attributes->get('_route') === 'app_login';
    }

    /**
     * Xử lý xác thực: lấy email/số điện thoại và mật khẩu từ form,
     * kiểm tra trong database.
     */
    public function authenticate(Request $request): Passport
    {
        $identifier = trim($request->request->get('email', ''));
        $password = $request->request->get('password');

        if (null === $identifier || null === $password) {
            throw new \Symfony\Component\Security\Core\Exception\AuthenticationException('Vui lòng nhập email/số điện thoại và mật khẩu.');
        }

        return new Passport(
            new UserBadge($identifier, function ($identifier) {
                if (empty($identifier)) {
                    throw new \Symfony\Component\Security\Core\Exception\AuthenticationException('Vui lòng nhập email hoặc số điện thoại.');
                }

                // Tìm user theo email hoặc số điện thoại
                $user = $this->accountRepository->findOneBy(['email' => $identifier]) ??
                    $this->accountRepository->findOneBy(['phoneNumber' => $identifier]);

                if (!$user) {
                    throw new \Symfony\Component\Security\Core\Exception\UserNotFoundException('Không tìm thấy tài khoản.');
                }

                return $user;
            }),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    /**
     * Khi đăng nhập thành công, chuyển hướng tới trang charts.
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }


    /**
     * Khi đăng nhập thất bại, quay lại trang login với lỗi.
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        if ($request->hasSession()) {
            // Lưu thông báo lỗi vào session thông thường
            $request->getSession()->set('login_error', 'Đăng nhập thất bại. Kiểm tra lại thông tin.');
        }
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }
}