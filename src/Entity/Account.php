<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AccountRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: User::class, mappedBy: "account", cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: false)]
    private string $fullName;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255, nullable: false)]
    private string $password;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $resetToken = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }


    public function setUser(?User $user): static
    {
        $this->user = $user;

        if ($user !== null) {
            if ($user->getAccount() !== $this) {
                $user->setAccount($this);
            }
            if ($user->getFullName() === null && $this->fullName !== null) {
                $user->setFullName($this->fullName);
            }
            if ($user->getEmail() === null && $this->email !== null) {
                $user->setEmail($this->email);
            }
            if ($user->getPhoneNumber() === null && $this->phoneNumber !== null) {
                $user->setPhoneNumber($this->phoneNumber);
            }
        }

        return $this;
    }


    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }


    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }


    public function getRoles(): array
    {
        $roles = $this->roles;
        // Đảm bảo rằng 'ROLE_USER' luôn có trong danh sách vai trò.
        if (!in_array('ROLE_USER', $roles, true)) {
            $roles[] = 'ROLE_USER';
        }
        return array_unique($roles);
    }


    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email ?: $this->phoneNumber ?: throw new \LogicException('User identifier cannot be null.');
    }

    public function eraseCredentials(): void
    {
        // Nếu có bất kỳ dữ liệu nhạy cảm nào, hãy xóa ở đây.
    }
}
