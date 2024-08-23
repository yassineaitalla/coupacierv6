<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\DevisRepository;

#[ORM\Entity(repositoryClass: DevisRepository::class)]
class Devis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $idclient = null;

    #[ORM\Column(length: 255)]
    private ?string $statut;

    #[ORM\OneToMany(targetEntity: DevisProduits::class, mappedBy: 'devis', cascade: ['persist', 'remove'])]
    private Collection $devisProduits;

    public function __construct()
    {
        $this->devisProduits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdclient(): ?Client
    {
        return $this->idclient;
    }

    public function setIdclient(?Client $idclient): self
    {
        $this->idclient = $idclient;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection<int, DevisProduits>
     */
    public function getDevisProduits(): Collection
    {
        return $this->devisProduits;
    }

    public function addDevisProduit(DevisProduits $devisProduit): self
    {
        if (!$this->devisProduits->contains($devisProduit)) {
            $this->devisProduits->add($devisProduit);
            $devisProduit->setDevis($this);
        }

        return $this;
    }

    public function removeDevisProduit(DevisProduits $devisProduit): self
    {
        if ($this->devisProduits->removeElement($devisProduit)) {
            // set the owning side to null (unless already changed)
            if ($devisProduit->getDevis() === $this) {
                $devisProduit->setDevis(null);
            }
        }

        return $this;
    }
}
