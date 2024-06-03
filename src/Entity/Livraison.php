<?php

namespace App\Entity;

use App\Repository\LivraisonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivraisonRepository::class)]
class Livraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'identrepot', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?entrepot $identrepot = null;

    #[ORM\ManyToOne(inversedBy: 'livraisons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?client $idclient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentrepot(): ?entrepot
    {
        return $this->identrepot;
    }

    public function setIdentrepot(entrepot $identrepot): static
    {
        $this->identrepot = $identrepot;

        return $this;
    }

    public function getIdclient(): ?client
    {
        return $this->idclient;
    }

    public function setIdclient(?client $idclient): static
    {
        $this->idclient = $idclient;

        return $this;
    }
}
