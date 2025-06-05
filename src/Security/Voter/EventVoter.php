<?php

namespace App\Security\Voter;

use App\Entity\Event;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

final class EventVoter extends Voter
{
    public const EDIT = 'EVENT_EDIT';
    public const VIEW = 'EVENT_VIEW';
    public const CREATE = 'EVENT_CREATE';
    public const DELETE = 'EVENT_DELETE';

    private Security $securityHelper; // Kiểm tra quyền người dùng

    public function __construct(Security $security)
    {
        $this->securityHelper = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::CREATE, self::DELETE], true)
            && $subject instanceof Event;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser(); // Lấy thông tin người dùng

        if (!$user instanceof UserInterface) {
            return false; // Người dùng chưa đăng nhập
        }

        /** @var Event $event */
        $event = $subject;

        return match ($attribute) {
            self::EDIT => $this->securityHelper->isGranted('ROLE_ADMIN'),
            self::CREATE => $this->securityHelper->isGranted('ROLE_ADMIN'),
            self::DELETE => $this->securityHelper->isGranted('ROLE_ADMIN'),
            default => false,
            self::VIEW => true // Ai cũng có thể xem
        };
    }
}
