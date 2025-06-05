<?php

namespace App\Entity;

use App\Repository\UnionAdminRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class UnionAdmin extends User
{
    #[ORM\OneToMany(mappedBy: 'unionAdmin', targetEntity: YouthUnionSecretary::class, cascade: ['persist'])]
    private Collection $youthUnionSecretaries;

    #[ORM\OneToMany(mappedBy: 'unionAdmin', targetEntity: Event::class)]
    private Collection $managedEvents;

    public function __construct()
    {
        $this->youthUnionSecretaries = new ArrayCollection();
        $this->managedEvents = new ArrayCollection();
    }

    // ===================== YouthUnionSecretary =====================
    public function getYouthUnionSecretaries(): Collection
    {
        return $this->youthUnionSecretaries;
    }

    public function addYouthUnionSecretary(YouthUnionSecretary $secretary): static
    {
        if (!$this->youthUnionSecretaries->contains($secretary)) {
            $this->youthUnionSecretaries->add($secretary);
            $secretary->setUnionAdmin($this);
        }
        return $this;
    }


    public function removeYouthUnionSecretary(YouthUnionSecretary $secretary): static
    {
        if ($this->youthUnionSecretaries->removeElement($secretary)) {
            if ($secretary->getUnionAdmin() === $this) {
                $secretary->setUnionAdmin(null);
            }
        }
        return $this;
    }

    // ===================== Managed Events =====================
    public function getManagedEvents(): Collection
    {
        return $this->managedEvents;
    }

    public function addManagedEvent(Event $event): static
    {
        if (!$this->managedEvents->contains($event)) {
            $this->managedEvents->add($event);
            $event->setUnionAdmin($this);
        }
        return $this;
    }

    public function removeManagedEvent(Event $event): static
    {
        if ($this->managedEvents->removeElement($event)) {
            if ($event->getUnionAdmin() === $this) {
                $event->setUnionAdmin(null);
            }
        }
        return $this;
    }
}
