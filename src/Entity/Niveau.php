<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\NiveauRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=NiveauRepository::class)
 */
class Niveau
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("show_grpcompetences")
     * @Groups("show_competences")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("show_grpcompetences")
     * @Groups("show_competences")
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("show_grpcompetences")
     * @Groups("show_competences")
     */
    private $critereDevaluation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("show_grpcompetences")
     * @Groups("show_competences")
     */
    private $groupDactions;

    /**
     * @ORM\ManyToOne(targetEntity=Competence::class, inversedBy="niveaux")
     * @ORM\JoinColumn(nullable=false)
     */
    private $competence;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCritereDevaluation(): ?string
    {
        return $this->critereDevaluation;
    }

    public function setCritereDevaluation(string $critereDevaluation): self
    {
        $this->critereDevaluation = $critereDevaluation;

        return $this;
    }

    public function getGroupDactions(): ?string
    {
        return $this->groupDactions;
    }

    public function setGroupDactions(string $groupDactions): self
    {
        $this->groupDactions = $groupDactions;

        return $this;
    }

    public function getCompetence(): ?Competence
    {
        return $this->competence;
    }

    public function setCompetence(?Competence $competence): self
    {
        $this->competence = $competence;

        return $this;
    }
}
