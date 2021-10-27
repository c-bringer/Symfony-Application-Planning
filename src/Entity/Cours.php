<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CoursRepository::class)
 */
class Cours
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $commenceA;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $finiA;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommenceA(): ?\DateTimeInterface
    {
        return $this->commenceA;
    }

    public function setCommenceA(\DateTimeInterface $commenceA): self
    {
        $this->commenceA = $commenceA;

        return $this;
    }

    public function getFiniA(): ?\DateTimeInterface
    {
        return $this->finiA;
    }

    public function setFiniA(?\DateTimeInterface $finiA): self
    {
        $this->finiA = $finiA;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }
}
