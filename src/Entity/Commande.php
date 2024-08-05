<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\ManyToOne(targetEntity: Produit::class, )]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    #[ORM\ManyToOne(targetEntity: CommandeF::class, )]
    #[ORM\JoinColumn(nullable: false)]
    private ?CommandeF $commandeF = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?float $totalTtc = null;

    #[ORM\Column(length: 255)]
    private ?string $adresseFacturation = null;

    #[ORM\Column(length: 100)]
    private ?string $villeFacturation = null;

    #[ORM\Column(length: 255)]
    private ?string $adresseLivraison = null;

    #[ORM\Column(length: 100)]
    private ?string $etat = null;

    #[ORM\Column(length: 10)]
    private ?string $codePostalFacturation = null;

    #[ORM\Column(length: 10)]
    private ?string $codePostalLivraison = null;

    #[ORM\Column(length: 100)]
    private ?string $paysFacturation = null;

    #[ORM\Column(type: 'integer')]
    private ?int $quantite = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?float $montantHorsTaxe = null;

    // Getters and setters...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;
        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;
        return $this;
    }

    public function getCommandeF(): ?CommandeF
    {
        return $this->commandeF;
    }

    public function setCommandeF(?CommandeF $commandeF): self
    {
        $this->commandeF = $commandeF;
        return $this;
    }

    public function getTotalTtc(): ?float
    {
        return $this->totalTtc;
    }

    public function setTotalTtc(?float $totalTtc): self
    {
        $this->totalTtc = $totalTtc;
        return $this;
    }

    public function getAdresseFacturation(): ?string
    {
        return $this->adresseFacturation;
    }

    public function setAdresseFacturation(?string $adresseFacturation): self
    {
        $this->adresseFacturation = $adresseFacturation;
        return $this;
    }

    public function getAdresseLivraison(): ?string
    {
        return $this->adresseLivraison;
    }

    public function setAdresseLivraison(?string $adresseLivraison): self
    {
        $this->adresseLivraison = $adresseLivraison;
        return $this;
    }

    public function getVilleFacturation(): ?string
    {
        return $this->villeFacturation;
    }

    public function setVilleFacturation(?string $villeFacturation): self
    {
        $this->villeFacturation = $villeFacturation;
        return $this;
    }

    public function getCodePostalFacturation(): ?string
    {
        return $this->codePostalFacturation;
    }

    public function setCodePostalFacturation(?string $codePostalFacturation): self
    {
        $this->codePostalFacturation = $codePostalFacturation;
        return $this;
    }

    public function getCodePostalLivraison(): ?string
    {
        return $this->codePostalLivraison;
    }

    public function setCodePostalLivraison(?string $codePostalLivraison): self
    {
        $this->codePostalLivraison = $codePostalLivraison;
        return $this;
    }

    public function getPaysFacturation(): ?string
    {
        return $this->paysFacturation;
    }

    public function setPaysFacturation(?string $paysFacturation): self
    {
        $this->paysFacturation = $paysFacturation;
        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;
        return $this;
    }

    public function getMontantHorsTaxe(): ?float
    {
        return $this->montantHorsTaxe;
    }

    public function setMontantHorsTaxe(?float $montantHorsTaxe): self
    {
        $this->montantHorsTaxe = $montantHorsTaxe;
        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;
        return $this;
    }
}
