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

    

    #[ORM\Column( length: 255)] // Définir le champ comme nullable
private ?string $messageClient = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $idClient = null;


    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Devis $IdDevis = null;




    #[ORM\Column(nullable: true, length: 255)]
    private ?string $messageVendeur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessageClient(): ?string
    {
        return $this->messageClient;
    }

    public function setMessageClient(string $messageClient): static
    {
        $this->messageClient = $messageClient;

        return $this;
    }

    public function getIdDevis(): ?Devis
    {
        return $this->IdDevis;
    }

    public function setIdDevis(?Devis $IdDevis): static
    {
        $this->IdDevis = $IdDevis;

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

    public function setMessageVendeur(string $messageVendeur): static
    {
        $this->messageVendeur = $messageVendeur;

        return $this;
    }
}
