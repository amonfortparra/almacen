<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $name = null;

    #[Assert\Unique]
    #[ORM\Column(unique:true)]
    private ?int $rack = null;

    #[Assert\Unique]
    #[ORM\Column(unique:true)]
    private ?int $line = null;

    #[Assert\Unique]
    #[ORM\Column(unique:true)]
    private ?int $block = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getRack(): ?int
    {
        return $this->rack;
    }

    public function setRack(int $rack): static
    {
        $this->rack = $rack;

        return $this;
    }

    public function getLine(): ?int
    {
        return $this->line;
    }

    public function setLine(int $line): static
    {
        $this->line = $line;

        return $this;
    }

    public function getBlock(): ?int
    {
        return $this->block;
    }

    public function setBlock(int $block): static
    {
        $this->block = $block;

        return $this;
    }
}
