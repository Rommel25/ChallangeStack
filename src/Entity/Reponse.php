<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Element;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $reponse_qcm = null;

    #[ORM\Column(nullable: true)]
    private ?bool $reponse_vf = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reponse_libre = null;

    #[ORM\ManyToOne(inversedBy: 'reponses')]
    private ?Question $question = null;

    #[ORM\ManyToOne(inversedBy: 'reponses')]
    private ?Eleve $eleve = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getReponseLibre(): ?string
    {
        return $this->reponse_libre;
    }

    public function setReponseLibre(?string $reponse_libre): static
    {
        $this->reponse_libre = $reponse_libre;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getEleve(): ?Eleve
    {
        return $this->eleve;
    }

    public function setEleve(?Eleve $eleve): static
    {
        $this->eleve = $eleve;

        return $this;
    }
}
