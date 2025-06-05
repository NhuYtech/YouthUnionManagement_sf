<?php

namespace App\Entity;

use App\Entity\Event;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\OptionsResolver\OptionsResolver;


#[ORM\Entity]
#[ORM\Table(name: "user")]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ORM\DiscriminatorMap([
    "user" => User::class,
    "union_admin" => UnionAdmin::class,
    "youth_union_secretary" => YouthUnionSecretary::class,
    "youth_member" => YouthMember::class
])]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: "user", cascade: ['persist', 'remove'])]
    private ?Account $account = null;

    #[ORM\ManyToMany(targetEntity: Event::class, inversedBy: 'participants')]
    private Collection $events;

    public function __construct()
    {
        // $this->fees = new ArrayCollection();
        // $this->recognitions = new ArrayCollection();
        $this->events = new ArrayCollection();
        // $this->evaluations = new ArrayCollection();
    }

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $unitName = null;

    #[ORM\Column(type: "string", length: 20)]
    private string $role = 'ROLE_USER';

    #[ORM\Column(length: 255)]
    private ?string $fullName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateOfBirth = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(length: 50)]
    private ?string $ethnicity = null;

    #[ORM\Column(length: 50)]
    private ?string $religion = null;

    #[ORM\Column(length: 50)]
    private ?string $citizenId = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $issueDate = null;

    #[ORM\Column(length: 255)]
    private ?string $placeOfIssue = null;

    #[ORM\Column(length: 255)]
    private ?string $hometownAddress = null;

    #[ORM\Column(length: 255)]
    private ?string $permanentAddress = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $regisNumber = null;

    #[ORM\Column(length: 100)]
    private ?string $joinPlace = null;

    #[ORM\Column(length: 255)]
    private ?string $cardPlace = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $joinDate = null;

    #[ORM\Column(length: 255)]
    private ?string $unionRole = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $association = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $honorMember = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $joinPartyDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $partyPosition = null;

    #[ORM\Column(nullable: true)]
    private ?bool $salaryStatus = null;

    #[ORM\Column(nullable: true)]
    private ?bool $unionBookNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $eduLevel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $proLevel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $polTheory = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $itLevel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $langLevel = null;

    #[ORM\Column(length: 255)]
    private ?string $job = null;

    #[ORM\Column(length: 20)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;
        return $this;
    }


    public function getEvents(): Collection
    {
        return $this->events;
    }
    public function setEvents(Collection $events): self
    {
        $this->events = $events;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getUnitName(): ?string
    {
        return $this->unitName;
    }

    public function setUnitName(string $unitName): static
    {
        $this->unitName = $unitName;
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

    public function __toString(): string
    {
        return $this->fullName ?? 'Unknown User';
    }


    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTimeInterface $dateOfBirth): static
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getEthnicity(): ?string
    {
        return $this->ethnicity;
    }

    public function setEthnicity(string $ethnicity): static
    {
        $this->ethnicity = $ethnicity;

        return $this;
    }

    public function getReligion(): ?string
    {
        return $this->religion;
    }

    public function setReligion(string $religion): static
    {
        $this->religion = $religion;

        return $this;
    }

    public function getCitizenId(): ?string
    {
        return $this->citizenId;
    }

    public function setCitizenId(string $citizenId): static
    {
        $this->citizenId = $citizenId;

        return $this;
    }

    public function getIssueDate(): ?\DateTimeInterface
    {
        return $this->issueDate;
    }

    public function setIssueDate(\DateTimeInterface $issueDate): static
    {
        $this->issueDate = $issueDate;

        return $this;
    }

    public function getPlaceOfIssue(): ?string
    {
        return $this->placeOfIssue;
    }

    public function setPlaceOfIssue(string $placeOfIssue): static
    {
        $this->placeOfIssue = $placeOfIssue;

        return $this;
    }

    public function getHometownAddress(): ?string
    {
        return $this->hometownAddress;
    }

    public function setHometownAddress(string $hometownAddress): static
    {
        $this->hometownAddress = $hometownAddress;

        return $this;
    }

    public function getPermanentAddress(): ?string
    {
        return $this->permanentAddress;
    }

    public function setPermanentAddress(string $permanentAddress): static
    {
        $this->permanentAddress = $permanentAddress;

        return $this;
    }

    public function getRegisNumber(): ?string
    {
        return $this->regisNumber;
    }

    public function setRegisNumber(?string $regisNumber): static
    {
        $this->regisNumber = $regisNumber;
        return $this;
    }


    public function getJoinPlace(): ?string
    {
        return $this->joinPlace;
    }

    public function setJoinPlace(string $joinPlace): static
    {
        $this->joinPlace = $joinPlace;

        return $this;
    }

    public function getCardPlace(): ?string
    {
        return $this->cardPlace;
    }

    public function setCardPlace(string $cardPlace): static
    {
        $this->cardPlace = $cardPlace;

        return $this;
    }

    public function getJoinDate(): ?\DateTimeInterface
    {
        return $this->joinDate;
    }

    public function setJoinDate(\DateTimeInterface $joinDate): static
    {
        $this->joinDate = $joinDate;

        return $this;
    }

    public function getUnionRole(): ?string
    {
        return $this->unionRole;
    }

    public function setUnionRole(string $unionRole): static
    {
        $this->unionRole = $unionRole;

        return $this;
    }

    public function getAssociation(): ?string
    {
        return $this->association;
    }

    public function setAssociation(?string $association): static
    {
        $this->association = $association;
        return $this;
    }


    public function getHonorMember(): ?string
    {
        return $this->honorMember;
    }

    public function setHonorMember(?string $honorMember): static
    {
        $this->honorMember = $honorMember;
        return $this;
    }


    public function getJoinPartyDate(): ?\DateTimeInterface
    {
        return $this->joinPartyDate;
    }

    public function setJoinPartyDate(?\DateTimeInterface $joinPartyDate): static
    {
        $this->joinPartyDate = $joinPartyDate;

        return $this;
    }

    public function getPartyPosition(): ?string
    {
        return $this->partyPosition;
    }

    public function setPartyPosition(?string $partyPosition): static
    {
        $this->partyPosition = $partyPosition;
        return $this;
    }


    public function getSalaryStatus(): ?bool
    {
        return $this->salaryStatus;
    }
    public function setSalaryStatus(?bool $salaryStatus): static
    {
        $this->salaryStatus = $salaryStatus;
        return $this;
    }

    public function getUnionBookNumber(): ?bool
    {
        return $this->unionBookNumber;
    }

    public function setUnionBookNumber(?bool $unionBookNumber): static
    {
        $this->unionBookNumber = $unionBookNumber;
        return $this;
    }

    public function getEduLevel(): ?string
    {
        return $this->eduLevel;
    }

    public function setEduLevel(?string $eduLevel): static
    {
        $this->eduLevel = $eduLevel;
        return $this;
    }

    public function getProLevel(): ?string
    {
        return $this->proLevel;
    }

    public function setProLevel(?string $proLevel): static
    {
        $this->proLevel = $proLevel;
        return $this;
    }

    public function getPolTheory(): ?string
    {
        return $this->polTheory;
    }

    public function setPolTheory(?string $polTheory): static
    {
        $this->polTheory = $polTheory;
        return $this;
    }

    public function getItLevel(): ?string
    {
        return $this->itLevel;
    }

    public function setItLevel(?string $itLevel): static
    {
        $this->itLevel = $itLevel;
        return $this;
    }


    public function getLangLevel(): ?string
    {
        return $this->langLevel;
    }

    public function setLangLevel(?string $langLevel): static
    {
        $this->langLevel = $langLevel;
        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(string $job): static
    {
        $this->job = $job;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->account ? $this->account->getPassword() : null;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    public function isSalaryStatus(): ?bool
    {
        return $this->salaryStatus;
    }

    public function isUnionBookNumber(): ?bool
    {
        return $this->unionBookNumber;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        $this->events->removeElement($event);

        return $this;
    }
}