<?php

namespace App\Entity;

use App\Repository\CommunicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Form;

#[ORM\Entity(repositoryClass: CommunicationRepository::class)]
class Communication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'communication')]
    private Collection $messages;

    #[ORM\ManyToOne(inversedBy: 'communications')]
    private ?Formateur $formateur = null;

    #[ORM\ManyToOne(inversedBy: 'communications')]
    private ?Eleve $eleve = null;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setCommunication($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getCommunication() === $this) {
                $message->setCommunication(null);
            }
        }

        return $this;
    }

    public function getFormateur(): ?formateur
    {
        return $this->formateur;
    }

    public function setFormateur(?formateur $formateur): static
    {
        $this->formateur = $formateur;

        return $this;
    }

    public function getEleve(): ?eleve
    {
        return $this->eleve;
    }

    public function setEleve(?eleve $eleve): static
    {
        $this->eleve = $eleve;

        return $this;
    }
}
