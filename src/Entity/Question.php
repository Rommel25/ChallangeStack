<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $intitule = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $reponse_qcm = null;

    #[ORM\Column(nullable: true)]
    private ?bool $reponse_vf = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    private ?Evaluation $evaluation = null;

    #[ORM\OneToMany(targetEntity: Reponse::class, mappedBy: 'question')]
    private Collection $reponses;

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(?string $intitule): static
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getReponseQcm(): ?array
    {
        return $this->reponse_qcm;
    }

    public function setReponseQcm(?array $reponse_qcm): static
    {
        $this->reponse_qcm = $reponse_qcm;

        return $this;
    }

    public function isReponseVf(): ?bool
    {
        return $this->reponse_vf;
    }

    public function setReponseVf(?bool $reponse_vf): static
    {
        $this->reponse_vf = $reponse_vf;

        return $this;
    }

    public function getEvaluation(): ?Evaluation
    {
        return $this->evaluation;
    }

    public function setEvaluation(?Evaluation $evaluation): static
    {
        $this->evaluation = $evaluation;

        return $this;
    }

    /**
     * @return Collection<int, Reponse>
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): static
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses->add($reponse);
            $reponse->setQuestion($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): static
    {
        if ($this->reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getQuestion() === $this) {
                $reponse->setQuestion(null);
            }
        }

        return $this;
    }
}
