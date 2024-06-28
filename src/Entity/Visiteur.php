<?php

namespace App\Entity;

use App\Repository\VisiteurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisiteurRepository::class)]
class Visiteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $pageVisiter = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPageVisiter(): ?string
    {
        return $this->pageVisiter;
    }

    public function setPageVisiter(string $pageVisiter): static
    {
        $this->pageVisiter = $pageVisiter;

        return $this;
    }
}
