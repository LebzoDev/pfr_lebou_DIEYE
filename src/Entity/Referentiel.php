<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReferentielRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 */
class Referentiel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"formateur_groupe","show_ref_formateur_group","apprenants_attente","show_apprenant_group","referentiel_competence","groupe_apprenants"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"formateur_groupe","show_ref_formateur_group","apprenants_attente","show_apprenant_group","referentiel_competence","groupe_apprenants"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"formateur_groupe","show_ref_formateur_group","apprenants_attente","show_apprenant_group","referentiel_competence","groupe_apprenants"})
     */
    private $presentation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"formateur_groupe","show_ref_formateur_group","apprenants_attente","show_apprenant_group","referentiel_competence","groupe_apprenants"})
     */
    private $programme;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"formateur_groupe","show_ref_formateur_group","apprenants_attente","show_apprenant_group","referentiel_competence","groupe_apprenants"})
     */
    private $criteres;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"formateur_groupe","show_ref_formateur_group","apprenants_attente","show_apprenant_group","referentiel_competence","groupe_apprenants"})
     */
    private $archive;

    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="referentiels")
     * @Groups({"referentiel_competence"})
     */
    private $competencesVisees;

    /**
     * @ORM\OneToMany(targetEntity=Promo::class, mappedBy="referentiel")
     */
    private $promos;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetences::class, inversedBy="referentiels")
     * @Groups({"referentiel_competence"})
     */
    private $groupeCompetences;

    public function __construct()
    {
        $this->setArchive(false);
        $this->competencesVisees = new ArrayCollection();
        $this->promos = new ArrayCollection();
        $this->groupeCompetences = new ArrayCollection();
    }

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

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getProgramme(): ?string
    {
        return $this->programme;
    }

    public function setProgramme(?string $programme): self
    {
        $this->programme = $programme;

        return $this;
    }

    public function getCriteres(): ?string
    {
        return $this->criteres;
    }

    public function setCriteres(?string $criteres): self
    {
        $this->criteres = $criteres;

        return $this;
    }

    public function getArchive(): ?bool
    {
        return $this->archive;
    }

    public function setArchive(bool $archive): self
    {
        $this->archive = $archive;

        return $this;
    }

    /**
     * @return Collection|Competence[]
     */
    public function getCompetencesVisees(): Collection
    {
        return $this->competencesVisees;
    }

    public function addCompetencesVisee(Competence $competencesVisee): self
    {
        if (!$this->competencesVisees->contains($competencesVisee)) {
            $this->competencesVisees[] = $competencesVisee;
        }

        return $this;
    }

    public function removeCompetencesVisee(Competence $competencesVisee): self
    {
        $this->competencesVisees->removeElement($competencesVisee);

        return $this;
    }

    /**
     * @return Collection|Promo[]
     */
    public function getPromos(): Collection
    {
        return $this->promos;
    }

    public function addPromo(Promo $promo): self
    {
        if (!$this->promos->contains($promo)) {
            $this->promos[] = $promo;
            $promo->setReferentiel($this);
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        if ($this->promos->removeElement($promo)) {
            // set the owning side to null (unless already changed)
            if ($promo->getReferentiel() === $this) {
                $promo->setReferentiel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GroupeCompetences[]
     */
    public function getGroupeCompetences(): Collection
    {
        return $this->groupeCompetences;
    }

    public function addGroupeCompetence(GroupeCompetences $groupeCompetence): self
    {
        if (!$this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences[] = $groupeCompetence;
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetences $groupeCompetence): self
    {
        $this->groupeCompetences->removeElement($groupeCompetence);

        return $this;
    }
}
