<?php

namespace App\Entity;

use App\Repository\CommandeFRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeFRepository::class)]
class CommandeF
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

 

    #[ORM\ManyToOne(inversedBy: 'clienid')]
    #[ORM\JoinColumn(nullable: false)]
    private ?client $idclientt = null;

    #[ORM\Column(length: 255)]
    private ?string $total = null;

    public function getId(): ?int
    {
        return $this->id;
    }

  

    public function getIdclientt(): ?client
    {
        return $this->idclientt;
    }

    public function setIdclientt(?client $idclientt): static
    {
        $this->idclientt = $idclientt;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): static
    {
        $this->total = $total;

        return $this;
    }
}
