<?php

namespace App\Entity;

use App\Repository\FormateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormateurRepository::class)]
class Formateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(targetEntity: Communication::class, mappedBy: 'formateur')]
    private Collection $communications;

    #[ORM\OneToMany(targetEntity: Cours::class, mappedBy: 'formateur')]
    private Collection $cours;

    #[ORM\OneToMany(targetEntity: Organisme::class, mappedBy: 'formateur')]
    private Collection $organisme;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $user = null;

    public function __toString(): string
    {
        return (string) $this->user;
    }

    public function __construct()
    {
        $this->communications = new ArrayCollection();
        $this->cours = new ArrayCollection();
        $this->organisme = new ArrayCollection();
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Communication>
     */
    public function getCommunications(): Collection
    {
        return $this->communications;
    }

    public function addCommunication(Communication $communication): static
    {
        if (!$this->communications->contains($communication)) {
            $this->communications->add($communication);
            $communication->setFormateur($this);
        }

        return $this;
    }

    public function removeCommunication(Communication $communication): static
    {
        if ($this->communications->removeElement($communication)) {
            // set the owning side to null (unless already changed)
            if ($communication->getFormateur() === $this) {
                $communication->setFormateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, cours>
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cours $cour): static
    {
        if (!$this->cours->contains($cour)) {
            $this->cours->add($cour);
            $cour->setFormateur($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): static
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getFormateur() === $this) {
                $cour->setFormateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, organisme>
     */
    public function getOrganisme(): Collection
    {
        return $this->organisme;
    }

    public function addOrganisme(Organisme $organisme): static
    {
        if (!$this->organisme->contains($organisme)) {
            $this->organisme->add($organisme);
            $organisme->setFormateur($this);
        }

        return $this;
    }

    public function removeOrganisme(Organisme $organisme): static
    {
        if ($this->organisme->removeElement($organisme)) {
            // set the owning side to null (unless already changed)
            if ($organisme->getFormateur() === $this) {
                $organisme->setFormateur(null);
            }
        }

        return $this;
    }
}
