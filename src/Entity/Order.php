<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
#[UniqueEntity('number')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(unique:true)]
    private ?int $number = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?picker $picker = null;

    #[ORM\Column]
    private ?\DateTime $final_date = null;

    /**
     * @var Collection<int, product>
     */
    #[ORM\ManyToMany(targetEntity: product::class)]
    private Collection $list_products;

    public function __construct()
    {
        $this->list_products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getPicker(): ?picker
    {
        return $this->picker;
    }

    public function setPicker(?picker $picker): static
    {
        $this->picker = $picker;

        return $this;
    }

    public function getFinalDate(): ?\DateTime
    {
        return $this->final_date;
    }

    public function setFinalDate(\DateTime $final_date): static
    {
        $this->final_date = $final_date;

        return $this;
    }

    /**
     * @return Collection<int, product>
     */
    public function getListProducts(): Collection
    {
        return $this->list_products;
    }

    public function addListProduct(product $listProduct): static
    {
        if (!$this->list_products->contains($listProduct)) {
            $this->list_products->add($listProduct);
        }

        return $this;
    }

    public function removeListProduct(product $listProduct): static
    {
        $this->list_products->removeElement($listProduct);

        return $this;
    }


    /**
     * @author Alejandro
     * 
     * Devuelve la lista de productos ordenada segun debe el picker recoger los productos
     */
    public function getListProductsOrderByProximity() {
        $products = $this->getListProducts()->getValues();
        usort($products, function($a, $b) {
            # Compara las estanterias
            if ($a->getRack() !== $b->getRack()) {
                return $a->getRack() <=> $b->getRack();
            }

            # Está comparando si es de las 4 primeras filas
            $aPrio = $a->getLine() <= 4;
            $bPrio = $a->getLine() <= 4;

            if ($aPrio && !$bPrio) return -1;
            if (!$aPrio && $bPrio) return 1;

            # Compara entre bloques
            if ($a->getBLock() !==$b->getBLock()) {
                return $a->getBLock() <=> $b->getBLock();
            }

            # Compara cual es la fila menor
            return $a->getLine() - $b->getLine();
        });

        return $products;
    }
}
