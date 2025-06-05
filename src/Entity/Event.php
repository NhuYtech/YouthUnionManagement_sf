<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\UnionAdmin;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Vich\Uploadable]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: UnionAdmin::class, inversedBy: 'managedEvents')]
    #[ORM\JoinColumn(nullable: true)]
    private ?UnionAdmin $unionAdmin = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'events')]
    private Collection $participants;
      
    #[ORM\Column(length: 255)]
    private ?string $eventName = null;

    #[ORM\Column(length: 255)]
    private ?string $eventType = null;

    #[ORM\Column(length: 255)]
    private ?string $organizationLevel = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[Vich\UploadableField(mapping: 'events', fileNameProperty: 'image')]
    private ?File $imageFile = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $rollCallTime = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pdfFileName = null;

    #[Vich\UploadableField(mapping: 'event_pdfs', fileNameProperty: 'pdfFileName')]
    private ?File $pdfFile = null;

    #[ORM\Column]
    private ?int $participantLimit = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $registerLink = null;

    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private ?string $status = null;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUnionAdmin(): ?UnionAdmin
    {
        return $this->unionAdmin;
    }

    public function setUnionAdmin(?UnionAdmin $unionAdmin): static
    {
        $this->unionAdmin = $unionAdmin;
        return $this;
    }


    public function getEventName(): ?string
    {
        return $this->eventName;
    }

    public function setEventName(string $eventName): static
    {
        $this->eventName = $eventName;
        return $this;
    }

    public function getEventType(): ?string
    {
        return $this->eventType;
    }

    public function setEventType(string $eventType): static
    {
        $this->eventType = $eventType;
        return $this;
    }

    public function getOrganizationLevel(): ?string
    {
        return $this->organizationLevel;
    }

    public function setOrganizationLevel(string $organizationLevel): static
    {
        $this->organizationLevel = $organizationLevel;
        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        if ($imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }



    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getRollCallTime(): ?\DateTimeInterface
    {
        return $this->rollCallTime;
    }

    public function setRollCallTime(\DateTimeInterface $rollCallTime): static
    {
        $this->rollCallTime = $rollCallTime;
        return $this;
    }

    public function getParticipantLimit(): ?int
    {
        return $this->participantLimit;
    }

    public function setParticipantLimit(int $participantLimit): static
    {
        $this->participantLimit = $participantLimit;
        return $this;
    }

    public function getPdfFile(): ?File
    {
        return $this->pdfFile;
    }

    public function setPdfFile(?File $pdfFile): self
    {
        $this->pdfFile = $pdfFile;
        if ($pdfFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
        return $this;
    }

    public function getPdfFileName(): ?string
    {
        return $this->pdfFileName;
    }

    public function setPdfFileName(?string $pdfFileName): self
    {
        $this->pdfFileName = $pdfFileName;
        return $this;
    }

    public function getRegisterLink(): ?string
    {
        return $this->registerLink;
    }

    public function setRegisterLink(?string $registerLink): static
    {
        $this->registerLink = $registerLink;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;
        return $this;
    }


    // Các phương thức để quản lý participants (tham gia sự kiện)
    public function addParticipant(User $user): self
    {
        if (!$this->participants->contains($user)) {
            $this->participants->add($user);
        }

        return $this;
    }

    public function removeParticipant(User $user): self
    {
        $this->participants->removeElement($user);

        return $this;
    }

}