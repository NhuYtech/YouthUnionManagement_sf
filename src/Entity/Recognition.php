<?php

namespace App\Entity;

use App\Repository\RecognitionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: RecognitionRepository::class)]
class Recognition
{

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $decisionNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $level = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $effectiveDate = null;

    #[ORM\Column(length: 255)]
    private ?string $reason = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fileDecision = null;

    #[Vich\UploadableField(mapping: 'recognition_pdfs', fileNameProperty: 'fileDecision')]
    private ?File $file = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile($file): self
    {
        $this->file = $file;
        return $this;
    }

    public function getFileDecision(): ?string
    {
        return $this->fileDecision;
    }

    public function setFileDecision(?string $fileDecision): self
    {
        $this->fileDecision = $fileDecision;

        return $this;
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDecisionNumber(): ?string
    {
        return $this->decisionNumber;
    }

    public function setDecisionNumber(string $decisionNumber): static
    {
        $this->decisionNumber = $decisionNumber;
        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): static
    {
        $this->level = $level;
        return $this;
    }

    public function getEffectiveDate(): ?\DateTimeInterface
    {
        return $this->effectiveDate;
    }

    public function setEffectiveDate(\DateTimeInterface $effectiveDate): static
    {
        $this->effectiveDate = $effectiveDate;
        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): static
    {
        $this->reason = $reason;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

}