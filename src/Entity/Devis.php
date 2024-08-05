<?php


namespace App\Entity;


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

    #[ORM\ManyToMany(targetEntity: Produit::class, inversedBy: 'devis')]
    private Collection $produits;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $Prixtotalligne = null;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'idDevis', orphanRemoval: true)]
    private Collection $messages;

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
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        $this->produits->removeElement($produit);

        return $this;
    }

    

    public function getPrixtotalligne(): ?string
    {
        return $this->Prixtotalligne;
    }

    public function setPrixtotalligne(?string $Prixtotalligne): static
    {
        $this->Prixtotalligne = $Prixtotalligne;

        return $this;
    }
}
