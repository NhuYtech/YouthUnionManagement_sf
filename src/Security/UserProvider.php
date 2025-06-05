<?php

namespace App\Security;

use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {      
        $repository = $this->entityManager->getRepository(Account::class);
        $user = $repository->findOneBy(['email' => $identifier])
            ?? $repository->findOneBy(['phoneNumber' => $identifier]);

        if (!$user) {
            throw new UserNotFoundException("Tài khoản không tồn tại.");
        }

        if (!$user instanceof UserInterface) {
            throw new \LogicException("Lớp Account phải implement UserInterface.");
        }

        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof Account) {
            throw new \LogicException("Tài khoản không hợp lệ.");
        }

        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return is_subclass_of($class, UserInterface::class) || $class === Account::class;
    }
}