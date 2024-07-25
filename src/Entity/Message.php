<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)] // Définir le champ comme nullable
    private ?string $messageClient = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $idClient = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Devis $idDevis = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $messageVendeur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessageClient(): ?string
    {
        return $this->messageClient;
    }

    public function setMessageClient(?string $messageClient): static
    {
        $this->messageClient = $messageClient;

        return $this;
    }

    public function getIdDevis(): ?Devis
    {
        return $this->idDevis;
    }

    public function setIdDevis(?Devis $idDevis): static
    {
        $this->idDevis = $idDevis;

        return $this;
    }

    public function getIdClient(): ?Client
    {
        return $this->idClient;
    }

    public function setIdClient(?Client $idClient): static
    {
        $this->idClient = $idClient;

        return $this;
    }

    public function getMessageVendeur(): ?string
    {
        return $this->messageVendeur;
    }

    public function setMessageVendeur(?string $messageVendeur): static
    {
        $this->messageVendeur = $messageVendeur;

        return $this;
    }
}
