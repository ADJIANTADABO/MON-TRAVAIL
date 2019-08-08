<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\RecepteurRepository")
 */
class Recepteur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomr;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenonr;

    /**
     * @ORM\Column(type="integer")
     */
    private $telephoner;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cni;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="Recepteur")
     */
    private $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomr(): ?string
    {
        return $this->nomr;
    }

    public function setNomr(string $nomr): self
    {
        $this->nomr = $nomr;

        return $this;
    }

    public function getPrenonr(): ?string
    {
        return $this->prenonr;
    }

    public function setPrenonr(string $prenonr): self
    {
        $this->prenonr = $prenonr;

        return $this;
    }

    public function getTelephoner(): ?int
    {
        return $this->telephoner;
    }

    public function setTelephoner(int $telephoner): self
    {
        $this->telephoner = $telephoner;

        return $this;
    }

    public function getCni(): ?string
    {
        return $this->cni;
    }

    public function setCni(string $cni): self
    {
        $this->cni = $cni;

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setRecepteur($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->contains($transaction)) {
            $this->transactions->removeElement($transaction);
            // set the owning side to null (unless already changed)
            if ($transaction->getRecepteur() === $this) {
                $transaction->setRecepteur(null);
            }
        }

        return $this;
    }
}
