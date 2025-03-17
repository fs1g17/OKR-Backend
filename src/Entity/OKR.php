<?php

namespace App\Entity;

use App\Repository\OKRRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OKRRepository::class)]
class OKR
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $okr = null;

    #[ORM\ManyToOne(targetEntity:User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $created_by = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOkr(): ?string
    {
        return $this->okr;
    }

    public function setOkr(string $okr): static
    {
        $this->okr = $okr;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    public function setCreatedBy(?User $created_by): static
    {
        $this->created_by = $created_by;

        return $this;
    }
}
