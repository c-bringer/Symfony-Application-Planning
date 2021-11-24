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

    /**
     * @ORM\ManyToOne(targetEntity=Intervenant::class, inversedBy="cours")
     */
    private $fkIntervenant;

    /**
     * @ORM\ManyToOne(targetEntity=Groupe::class, inversedBy="cours")
     */
    private $fkGroupe;

    /**
     * @ORM\ManyToOne(targetEntity=Matiere::class, inversedBy="cours")
     */
    private $fkMatiere;

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

    public function getFkIntervenant(): ?Intervenant
    {
        return $this->fkIntervenant;
    }

    public function setFkIntervenant(?Intervenant $fkIntervenant): self
    {
        $this->fkIntervenant = $fkIntervenant;

        return $this;
    }

    public function getFkGroupe(): ?Groupe
    {
        return $this->fkGroupe;
    }

    public function setFkGroupe(?Groupe $fkGroupe): self
    {
        $this->fkGroupe = $fkGroupe;

        return $this;
    }

    public function getFkMatiere(): ?Matiere
    {
        return $this->fkMatiere;
    }

    public function setFkMatiere(?Matiere $fkMatiere): self
    {
        $this->fkMatiere = $fkMatiere;

        return $this;
    }
}
