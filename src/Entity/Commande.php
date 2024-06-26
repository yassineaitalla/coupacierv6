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

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\OneToMany(targetEntity: BordereauLivraison::class, mappedBy: 'commande', orphanRemoval: true)]
    private Collection $bordereauxLivraison;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?float $totalTtc = null;

    #[ORM\Column(length: 255)]
    private ?string $adresseFacturation = null;

    #[ORM\Column(length: 100)]
    private ?string $villeFacturation = null;

    #[ORM\Column(length: 255)]
    private ?string $adresseLivraison = null;

    #[ORM\Column(length: 100)]
    private ?string $Etat = null;

    #[ORM\Column(length: 10)]
    private ?string $codePostalFacturation = null;

    #[ORM\Column(length: 10)]
    private ?string $codePostalLivraison = null;

    #[ORM\Column(length: 100)]
    private ?string $paysFacturation = null;

    #[ORM\Column]
    private ?int $quantite = null;

  

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

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection<int, BordereauLivraison>
     */
    

    public function getTotalTtc(): ?float
    {
        return $this->totalTtc;
    }

    public function setTotalTtc(?float $totalTtc): static
    {
        $this->totalTtc = $totalTtc;

        return $this;
    }

    public function getAdresseFacturation(): ?string
    {
        return $this->adresseFacturation;
    }

    public function setAdresseFacturation(?string $adresseFacturation): static
    {
        $this->adresseFacturation = $adresseFacturation;

        return $this;
    }

    public function getadresseLivraison(): ?string
    {
        return $this->adresseLivraison;
    }

    public function setadresseLivraison(?string $adresseLivraison): static
    {
        $this->adresseLivraison = $adresseLivraison;

        return $this;
    }


    public function getVilleFacturation(): ?string
    {
        return $this->villeFacturation;
    }

    public function setVilleFacturation(?string $villeFacturation): static
    {
        $this->villeFacturation = $villeFacturation;

        return $this;
    }

    public function getCodePostalFacturation(): ?string
    {
        return $this->codePostalFacturation;
    }

    public function setCodePostalFacturation(?string $codePostalFacturation): static
    {
        $this->codePostalFacturation = $codePostalFacturation;

        return $this;
    }

    public function getcodePostalLivraison(): ?string
    {
        return $this-> codePostalLivraison;
    }

    public function setcodePostalLivraison(?string $codePostalLivraison): static
    {
        $this-> codePostalLivraison = $codePostalLivraison;

        return $this;
    }


   
    public function getPaysFacturation(): ?string
    {
        return $this->paysFacturation;
    }

    public function setPaysFacturation(?string $paysFacturation): static
    {
        $this->paysFacturation = $paysFacturation;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    #[ORM\ManyToMany(targetEntity: Produit::class, inversedBy: 'commandes')]
    #[ORM\JoinTable(name: 'commande_produit')]
    private Collection $produits;

    public function __construct() // on initialise ses proprietes pour dire quelles sont pretes a etre uiliser
    {
        $this->bordereauxLivraison = new ArrayCollection();
        $this->produits = new ArrayCollection();
    }

    

    public function getMontantHorsTaxe(): ?float
    {
        return $this->montantHorsTaxe;
    }

    public function setMontantHorsTaxe(?float $montantHorsTaxe): static
    {
        $this->montantHorsTaxe = $montantHorsTaxe;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->Etat;
    }

    public function setEtat(?string $Etat): static
    {
        $this->Etat = $Etat;

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
}

