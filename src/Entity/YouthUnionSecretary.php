<?php

namespace App\Entity;

use App\Repository\YouthUnionSecretaryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class YouthUnionSecretary extends User
{

    #[ORM\OneToMany(mappedBy: "youthUnionSecretary", targetEntity: TrainingEvaluation::class)]
    private Collection $evaluations;

    #[ORM\OneToMany(mappedBy: 'youthUnionSecretary', targetEntity: Fee::class, cascade: ['persist', 'remove'])]
    private Collection $fees;

    #[ORM\ManyToOne(targetEntity: UnionAdmin::class, inversedBy: 'youthUnionSecretaries')]
    #[ORM\JoinColumn(name: "union_admin_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?UnionAdmin $unionAdmin = null;

    #[ORM\OneToMany(mappedBy: "youthUnionSecretary", targetEntity: InstructionDocument::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $instructionDocuments;

    #[ORM\ManyToMany(targetEntity: InstructionDocument::class, mappedBy: "viewedBy")]
    private Collection $viewedDocuments;

    #[ORM\OneToMany(mappedBy: "youthUnionSecretary", targetEntity: EventRollCall::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $rollCalls;

    public function __construct()
    {
        $this->fees = new ArrayCollection();
        $this->instructionDocuments = new ArrayCollection();
        $this->viewedDocuments = new ArrayCollection();
        $this->rollCalls = new ArrayCollection();
    }
    // ===================== UnionAdmin =====================
    public function getUnionAdmin(): ?UnionAdmin
    {
        return $this->unionAdmin;
    }

    public function setUnionAdmin(?UnionAdmin $unionAdmin): static
    {
        $this->unionAdmin = $unionAdmin;
        return $this;
    }

    // ===================== Fees =====================
    public function getFees(): Collection
    {
        return $this->fees;
    }

    public function addFee(Fee $fee): self
    {
        if (!$this->fees->contains($fee)) {
            $this->fees->add($fee);
            $fee->setYouthUnionSecretary($this);
        }
        return $this;
    }

    public function removeFee(Fee $fee): self
    {
        if ($this->fees->removeElement($fee)) {
            if ($fee->getYouthUnionSecretary() === $this) {
                $fee->setYouthUnionSecretary(null);
            }
        }
        return $this;
    }

    // ===================== InstructionDocuments =====================
    public function getInstructionDocuments(): Collection
    {
        return $this->instructionDocuments;
    }

    public function addInstructionDocument(InstructionDocument $instructionDocument): self
    {
        if (!$this->instructionDocuments->contains($instructionDocument)) {
            $this->instructionDocuments->add($instructionDocument);
            $instructionDocument->setYouthUnionSecretary($this);
        }
        return $this;
    }

    public function removeInstructionDocument(InstructionDocument $instructionDocument): self
    {
        if ($this->instructionDocuments->removeElement($instructionDocument)) {
            if ($instructionDocument->getYouthUnionSecretary() === $this) {
                $instructionDocument->setYouthUnionSecretary(null);
            }
        }
        return $this;
    }

    // ===================== ViewedDocuments (ManyToMany) =====================
    public function getViewedDocuments(): Collection
    {
        return $this->viewedDocuments;
    }

    public function addViewedDocument(InstructionDocument $document): self
    {
        if (!$this->viewedDocuments->contains($document)) {
            $this->viewedDocuments->add($document);
        }
        return $this;
    }

    public function removeViewedDocument(InstructionDocument $document): self
    {
        $this->viewedDocuments->removeElement($document);
        return $this;
    }

    public function getRollCalls(): Collection
    {
        return $this->rollCalls;
    }

    public function addRollCall(EventRollCall $rollCall): self
    {
        if (!$this->rollCalls->contains($rollCall)) {
            $this->rollCalls->add($rollCall);
            $rollCall->setYouthUnionSecretary($this);
        }
        return $this;
    }

    public function removeRollCall(EventRollCall $rollCall): self
    {
        if ($this->rollCalls->removeElement($rollCall)) {
            if ($rollCall->getYouthUnionSecretary() === $this) {
                $rollCall->setYouthUnionSecretary(null);
            }
        }
        return $this;
    }
}