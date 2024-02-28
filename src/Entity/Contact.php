<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 60)]
    private ?string $prenom = null;

    #[ORM\Column(length: 60)]
    private ?string $email = null;

    #[ORM\Column(length: 50)]
    private ?string $telephone = null;

    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'contact')]
    private Collection $messages;

    #[ORM\Column]
    private ?bool $estSupprime = false;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
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
            $message->setContact($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getContact() === $this) {
                $message->setContact(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return "nom:$this->nom prenom:$this->prenom email:$this->email tel:$this->telephone";
    }

    public function estSupprime(): ?bool
    {
        return $this->estSupprime;
    }

    public function setEstSupprime(bool $estSupprime): static
    {
        $this->estSupprime = $estSupprime;

        return $this;
    }
}
