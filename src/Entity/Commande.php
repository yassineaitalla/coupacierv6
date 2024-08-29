<?php



namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\ManyToOne(targetEntity: Produit::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    #[ORM\ManyToOne(targetEntity: CommandeF::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?CommandeF $commandeF = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?float $prix = null;

    #[ORM\Column(type: 'integer')]
    private ?int $quantite = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $Surmesure = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?float $montantHorsTaxe = null;

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

    
    public function getSurmesure(): ?float
    {
        return $this->Surmesure;
    }

    public function setSurmesure(?float $Surmesure): static   // ---> ? le champ peut etre null
    {
        $this->Surmesure = $Surmesure;

        return $this;
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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;
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
}
