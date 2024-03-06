<?php

namespace App\Entity;

use App\Repository\ClasseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClasseRepository::class)]
class Classe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $section = null;

    #[ORM\ManyToMany(targetEntity: Eleve::class, mappedBy: 'classes')]
    private Collection $eleves;

    #[ORM\OneToMany(targetEntity: Creneau::class, mappedBy: 'classe')]
    private Collection $creneaux;

    #[ORM\ManyToOne(inversedBy: 'classes')]
    private ?Formation $formation = null;

    public function __toString(): string
    {
        return $this->section . $this->id;
    }


    public function __construct()
    {
        $this->eleves = new ArrayCollection();
        $this->creneaux = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSection(): ?string
    {
        return $this->section;
    }

    public function setSection(?string $section): static
    {
        $this->section = $section;

        return $this;
    }

    /**
     * @return Collection<int, eleve>
     */
    public function getEleves(): Collection
    {
        return $this->eleves;
    }

    public function addElefe(Eleve $elefe): static
    {
        if (!$this->eleves->contains($elefe)) {
            $this->eleves->add($elefe);
        }

        return $this;
    }

    public function removeElefe(Eleve $elefe): static
    {
        $this->eleves->removeElement($elefe);

        return $this;
    }

    /**
     * @return Collection<int, creneau>
     */
    public function getCreneaux(): Collection
    {
        return $this->creneaux;
    }

    public function addCreneaux(Creneau $creneaux): static
    {
        if (!$this->creneaux->contains($creneaux)) {
            $this->creneaux->add($creneaux);
            $creneaux->setClasse($this);
        }

        return $this;
    }

    public function removeCreneaux(Creneau $creneaux): static
    {
        if ($this->creneaux->removeElement($creneaux)) {
            // set the owning side to null (unless already changed)
            if ($creneaux->getClasse() === $this) {
                $creneaux->setClasse(null);
            }
        }

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): static
    {
        $this->formation = $formation;

        return $this;
    }
}
