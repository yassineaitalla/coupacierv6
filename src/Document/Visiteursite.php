<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class Visiteursite
{
    #[MongoDB\Id]
    private ?string $id = null;

    #[MongoDB\Field(type: 'string')]
    private ?string $pageVisiter = null;

    #[MongoDB\Field(type: 'date')]
    private \DateTime $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime(); // Initialise la date de création a l'heure actuelle
    }

    // Getters and setters

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPageVisiter(): ?string
    {
        return $this->pageVisiter;
    }

    public function setPageVisiter(string $pageVisiter): self
    {
        $this->pageVisiter = $pageVisiter;
        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
