<?php

namespace App\Entity;

use App\Repository\FeeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;


#[ORM\Entity(repositoryClass: FeeRepository::class)]
class Fee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: YouthUnionSecretary::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?YouthUnionSecretary $youthUnionSecretary = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false)]
    private ?\DateTimeInterface $paymentDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $paymentMethod = null;

    #[ORM\Column]
    private ?int $memberCount = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $note = null;

    #[ORM\Column(type: 'string', length: 50)]
    private ?string $status = null;


    // Getter và Setter
    public function __construct()
    {
        $this->paymentDate = new \DateTime();
        $this->status = 'draft';
    }

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
        return $this;
    }

    public function getUnitName(): ?string
    {
        return $this->youthUnionSecretary ? $this->youthUnionSecretary->getUnitName() : null;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;
        return $this;
    }
    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?string $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }
    public function getPaymentDate(): ?\DateTimeInterface
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(\DateTimeInterface $paymentDate): static
    {
        $this->paymentDate = $paymentDate;
        return $this;
    }
    public function getMemberCount(): ?int
    {
        return $this->memberCount;
    }

    public function setMemberCount(int $memberCount): static
    {
        $this->memberCount = $memberCount;
        return $this;
    }

    public function getSubmissionDate(): ?\DateTimeInterface
    {
        return $this->paymentDate;
    }

    public function setSubmissionDate(\DateTimeInterface $submissionDate): static
    {
        $this->paymentDate = $submissionDate;
        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): static
    {
        $this->note = $note;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getYouthUnionSecretary(): ?YouthUnionSecretary
    {
        return $this->youthUnionSecretary;
    }

    public function setYouthUnionSecretary(?YouthUnionSecretary $youthUnionSecretary): self
    {
        $this->youthUnionSecretary = $youthUnionSecretary;
        return $this;
    }

}
