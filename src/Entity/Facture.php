<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
#[ORM\Entity]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $adresseFacturation = null;

    #[ORM\Column(length: 100)]
    private ?string $villeFacturation = null;

    #[ORM\Column(length: 10)]
    private ?string $codePostalFacturation = null;

    #[ORM\Column(length: 100)]
    private ?string $paysFacturation = null;

    #[ORM\Column(length: 255)]
    private ?string $adresseLivraison = null;

    #[ORM\Column(length: 100)]
    private ?string $villeLivraison = null;

    #[ORM\Column(length: 10)]
    private ?string $codePostalLivraison = null;

    #[ORM\Column(length: 100)]
    private ?string $paysLivraison = null;

    #[ORM\ManyToOne(targetEntity: CommandeF::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?CommandeF $commandeF = null;

    // Getters and setters...

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPaysFacturation(): ?string
    {
        return $this->paysFacturation;
    }

    public function setPaysFacturation(?string $paysFacturation): self
    {
        $this->paysFacturation = $paysFacturation;
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

    public function getVilleLivraison(): ?string
    {
        return $this->villeLivraison;
    }

    public function setVilleLivraison(?string $villeLivraison): self
    {
        $this->villeLivraison = $villeLivraison;
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

    public function getPaysLivraison(): ?string
    {
        return $this->paysLivraison;
    }

    public function setPaysLivraison(?string $paysLivraison): self
    {
        $this->paysLivraison = $paysLivraison;
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
}
