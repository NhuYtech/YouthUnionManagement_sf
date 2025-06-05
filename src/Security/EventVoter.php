<?php
namespace App\Security;

use App\Entity\Event;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;


class EventVoter extends Voter
{
    const CREATE = 'create';
    const EDIT = 'edit';
    const DELETE = 'delete';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::CREATE, self::EDIT, self::DELETE])) {
            return false;
        }

        if ($subject !== null && !$subject instanceof Event) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // Kiểm tra nếu người dùng chưa đăng nhập
        if (!$user instanceof UserInterface) {
            return false;
        }

        // Kiểm tra quyền nếu là admin
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        $event = $subject; // Subject là đối tượng Event

        switch ($attribute) {
            case self::CREATE:
                return $this->canCreate($user);
            case self::EDIT:
                return $this->canEdit($event, $user);
            case self::DELETE:
                return $this->canDelete($event, $user);
        }

        return false;
    }

    private function canCreate(UserInterface $user): bool
    {
        // Chỉ người dùng có ROLE_EVENT_EDITOR mới có quyền tạo
        return $this->security->isGranted('ROLE_EVENT_EDITOR');
    }

    private function canEdit(?Event $event, UserInterface $user): bool
    {
        // Người dùng có ROLE_EVENT_EDITOR mới có quyền chỉnh sửa
        return $this->security->isGranted('ROLE_EVENT_EDITOR');
    }

    private function canDelete(?Event $event, UserInterface $user): bool
    {
        // Chỉ người dùng có ROLE_EVENT_MANAGER mới có quyền xóa
        return $this->security->isGranted('ROLE_EVENT_MANAGER');
    }
}
