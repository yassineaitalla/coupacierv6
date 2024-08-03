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

    #[ORM\ManyToMany(targetEntity: Produit::class, inversedBy: 'devis')]
    private Collection $produits;

    // Ajout de la colonne id_produit spécifique (facultatif)
    #[ORM\ManyToOne(targetEntity: Produit::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Produit $produitSpecifique = null;

    // Ajout de la colonne quantite
    #[ORM\Column(type: 'integer')]
    
    private ?int $quantite = null;

    // Ajout de la colonne surMesure
    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $Surmesure = null;

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

    // Méthodes pour la relation avec produitSpecifique
    public function getProduitSpecifique(): ?Produit
    {
        return $this->produitSpecifique;
    }

    public function setProduitSpecifique(?Produit $produitSpecifique): self
    {
        $this->produitSpecifique = $produitSpecifique;

        return $this;
    }



    // Méthodes pour la colonne quantite
    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    // Méthodes pour la colonne surMesure
    public function getSurmesure(): ?float
    {
        return $this->Surmesure;
    }

    public function setSurmesure(?float $Surmesure): static   // ---> ? le champ peut etre null
    {
        $this->Surmesure = $Surmesure;

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
            $message->setIdDevis($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            if ($message->getIdDevis() === $this) {
                $message->setIdDevis(null);
            }
        }

        return $this;
    }


    #[ORM\Column(nullable: true)] // Définit la colonne comme nullable
private ?string $Prixtotalligne = null;

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
