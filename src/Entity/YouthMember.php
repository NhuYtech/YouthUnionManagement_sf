<?php

namespace App\Entity;

use App\Repository\YouthMemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: YouthMemberRepository::class)]
class YouthMember extends User
{
    #[ORM\OneToMany(targetEntity: EventRollCall::class, mappedBy: 'youthMember', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $rollCalls;
    public function __construct()
    {
        parent::__construct();
        // $this->events = new ArrayCollection();
        $this->rollCalls = new ArrayCollection();
    }

    #[ORM\OneToMany(mappedBy: "youthMember", targetEntity: TrainingEvaluation::class)]
    private Collection $evaluations;



    // ===================== Event =====================
    public function getEvents(): Collection
    {
        return parent::getEvents(); // Sử dụng events từ Personal
    }

    public function registerEvent(Event $event): self
    {
        $events = $this->getEvents(); // Lấy danh sách sự kiện từ Personal
        if (!$events->contains($event)) {
            $events->add($event);
        }
        return $this;
    }

    public function cancelEvent(Event $event): self
    {
        $this->getEvents()->removeElement($event);
        return $this;
    }


    // ===================== EventRollCall =====================
    public function getRollCalls(): Collection
    {
        return $this->rollCalls;
    }

    public function addRollCall(EventRollCall $rollCall): self
    {
        if (!$this->rollCalls->contains($rollCall)) {
            $this->rollCalls->add($rollCall);
            $rollCall->setYouthMember($this);
        }
        return $this;
    }

    public function removeRollCall(EventRollCall $rollCall): self
    {
        if ($this->rollCalls->removeElement($rollCall)) {
            if ($rollCall->getYouthMember() === $this) {
                $rollCall->setYouthMember(null);
            }
        }
        return $this;
    }

    // ===================== User Actions =====================
    public function login(): void
    {
    }
    public function logout(): void
    {
    }
    public function register(): void
    {
    }
    public function updateProfile(): void
    {
    }
    public function forgotPassword(): void
    {
    }
    public function changePassword(): void
    {
    }

    // ===================== Document Actions =====================
    public function downloadDocument(): void
    {
    }

    public function __toString(): string
    {
        return $this->name ?? 'Không có tên';
    }


}
