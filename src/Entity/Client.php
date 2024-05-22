<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null; 

    #[ORM\Column(length: 255)]
    private ?string $civilite = null; 

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private string $telephone;

    #[ORM\Column(length: 255)]
    private ?string $email;

    #[ORM\Column(length: 255)]
    private ?string $Adresse;

    #[ORM\Column(length: 255)]
    private ?string $Codepostal;

    #[ORM\Column(length: 255)]
    private ?string $Ville;

    


    #[ORM\Column(length: 255)]
    private ?string $motdepasse;

    #[ORM\OneToMany(targetEntity: Societe::class, mappedBy: "client")]
    private $societes;

    public function __construct()
    {
        $this->societes = new ArrayCollection();
        $this->messages = new ArrayCollection();
        
        $this->idclient = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(string $civilite): self
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(string $Adresse): self
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCodepostal(): ?string
    {
        return $this-> Codepostal;
    }

    public function setCodepostal(string $Codepostal): self
    {
        $this-> Codepostal = $Codepostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this-> Ville;
    }

    public function setVille(string $Ville): self
    {
        $this-> Ville = $Ville;

        return $this;
    }

   

    

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    #[ORM\Column(length: 255)]
    private ?string $typeclient;

    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'idClient', orphanRemoval: true)]
    private Collection $messages;

   

    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'idclient', orphanRemoval: true)]
    private Collection $idclient;

    public function gettypeclient(): ?string
    {
        return $this->typeclient;
    }

    public function settypeclient(string $typeclient): self
    {
        $this->typeclient = $typeclient;

        return $this;
    }

    public function getMotdepasse(): ?string
    {
        return $this->motdepasse;
    }

    public function setMotdepasse(string $motdepasse): self
    {
        $this->motdepasse = $motdepasse;

        return $this;
    }

    /**
     * @return Collection|Societe[]
     */
    public function getSocietes(): Collection
    {
        return $this->societes;
    }

    public function addSociete(Societe $societe): self
    {
        if (!$this->societes->contains($societe)) {
            $this->societes[] = $societe;
            $societe->setClient($this);
        }

        return $this;
    }

    public function removeSociete(Societe $societe): self
    {
        if ($this->societes->removeElement($societe)) {
            // set the owning side to null (unless already changed)
            if ($societe->getClient() === $this) {
                $societe->setClient(null);
            }
        }

        return $this;
    }

    // Implémentation des méthodes de l'interface UserInterface

    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        // Définissez les rôles de l'utilisateur ici, par exemple ['ROLE_USER']
        return ['ROLE_USER'];
    }

    public function getPassword(): string
    {
        return (string) $this->motdepasse;
    }

    public function getSalt()
    {
        // Vous n'avez pas besoin de sel car bcrypt gère cela automatiquement
    }

    public function eraseCredentials(): void
{
    // Si vous stockez des données sensibles dans cet objet, nettoyez-les ici
    // Cette méthode est nécessaire car UserInterface l'exige
}

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
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
            $message->setIdClient($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getIdClient() === $this) {
                $message->setIdClient(null);
            }
        }

        return $this;
    }

 

    /**
     * @return Collection<int, Commande>
     */
    public function getIdclient(): Collection
    {
        return $this->idclient;
    }

    public function addIdclient(Commande $idclient): static
    {
        if (!$this->idclient->contains($idclient)) {
            $this->idclient->add($idclient);
            $idclient->setIdclient($this);
        }

        return $this;
    }

    public function removeIdclient(Commande $idclient): static
    {
        if ($this->idclient->removeElement($idclient)) {
            // set the owning side to null (unless already changed)
            if ($idclient->getIdclient() === $this) {
                $idclient->setIdclient(null);
            }
        }

        return $this;
    }
}
