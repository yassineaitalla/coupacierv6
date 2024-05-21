<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'idclient')]
    #[ORM\JoinColumn(nullable: false)]
    private ?client $idclient = null;

    #[ORM\OneToMany(targetEntity: BordereauLivraison::class, mappedBy: 'idcommande', orphanRemoval: true)]
    private Collection $idcommande;

    public function __construct()
    {
        $this->idcommande = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, BordereauLivraison>
     */
    public function getIdcommande(): Collection
    {
        return $this->idcommande;
    }

    public function addIdcommande(BordereauLivraison $idcommande): static
    {
        if (!$this->idcommande->contains($idcommande)) {
            $this->idcommande->add($idcommande);
            $idcommande->setIdcommande($this);
        }

        return $this;
    }

    public function removeIdcommande(BordereauLivraison $idcommande): static
    {
        if ($this->idcommande->removeElement($idcommande)) {
            // set the owning side to null (unless already changed)
            if ($idcommande->getIdcommande() === $this) {
                $idcommande->setIdcommande(null);
            }
        }

        return $this;
    }
}
