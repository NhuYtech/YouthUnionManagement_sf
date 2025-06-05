<?php

namespace App\Entity;

use App\Entity\User;
use App\Repository\TrainingEvaluationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrainingEvaluationRepository::class)]
class TrainingEvaluation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $strengths = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $weaknesses = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $reward = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $discipline = null;

    #[ORM\Column(length: 120)]
    private ?string $finalEvaluate = null;


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

    public function getStrengths(): ?string
    {
        return $this->strengths;
    }

    public function setStrengths(string $strengths): static
    {
        $this->strengths = $strengths;
        return $this;
    }

    public function getWeaknesses(): ?string
    {
        return $this->weaknesses;
    }

    public function setWeaknesses(string $weaknesses): static
    {
        $this->weaknesses = $weaknesses;
        return $this;
    }

    public function getReward(): ?string
    {
        return $this->reward;
    }

    public function setReward(string $reward): static
    {
        $this->reward = $reward;
        return $this;
    }

    public function getDiscipline(): ?string
    {
        return $this->discipline;
    }

    public function setDiscipline(string $discipline): static
    {
        $this->discipline = $discipline;
        return $this;
    }

    public function getFinalEvaluate(): ?string
    {
        return $this->finalEvaluate;
    }

    public function setFinalEvaluate(string $finalEvaluate): static
    {
        $this->finalEvaluate = $finalEvaluate;
        return $this;
    }

}
