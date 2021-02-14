<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReferentielRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ApiResource(
 *      routePrefix="/admin",
 *      normalizationContext={"groups"={"show_referentiels"}},
 *      collectionOperations={
 *          "get"={
 *               "normalization_context"={"groups"={"show_referentiels"}},
 *           },
 *          "post"={
 *                 "security"="is_granted('ROLE_ADMINISTRATEUR') or is_granted('ROLE_CM')",
 *                 "security_message"="Vous n'avez pas acces à cette ressource",
 *                 "path"="/referentiels",
 *                 "controller"="App\Controller\ReferentileController::addReferentiel",
 *                 "deserialize"=false,
 *           },
 *      },
 *      itemOperations={
 *          "get","put","delete",   
 *      }
 * )
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 * @ApiFilter(BooleanFilter::class, properties={"archive"})
 */
class Referentiel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"show_referentiels","formateur_groupe","show_ref_formateur_group","apprenants_attente","show_apprenant_group","referentiel_competence","groupe_apprenants"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show_referentiels","formateur_groupe","show_ref_formateur_group","apprenants_attente","show_apprenant_group","referentiel_competence","groupe_apprenants"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show_referentiels","formateur_groupe","show_ref_formateur_group","apprenants_attente","show_apprenant_group","referentiel_competence","groupe_apprenants"})
     */
    private $presentation;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"show_referentiels","formateur_groupe","show_ref_formateur_group","apprenants_attente","show_apprenant_group","referentiel_competence","groupe_apprenants"})
     */
    private $programme;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"show_referentiels","formateur_groupe","show_ref_formateur_group","apprenants_attente","show_apprenant_group","referentiel_competence","groupe_apprenants"})
     */
    private $archive;

    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="referentiels")
     * @Groups({"referentiel_competence","show_referentiels"})
     */
    private $competencesVisees;

    /**
     * @ORM\OneToMany(targetEntity=Promo::class, mappedBy="referentiel")
     * @ApiSubresource()
     */
    private $promos;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetences::class, inversedBy="referentiels")
     * @ApiSubresource()
     * @Groups({"referentiel_competence","show_referentiels"})
     */
    private $groupeCompetences;

    /**
     * @ORM\OneToMany(targetEntity=CriteresReferentiel::class, mappedBy="referenceReferentiel")
     * @Groups({"show_referentiels"})
     * @ApiSubresource()
     */
    private $criteresReferentiels;

    public function __construct()
    {
        $this->setArchive(false);
        $this->competencesVisees = new ArrayCollection();
        $this->promos = new ArrayCollection();
        $this->groupeCompetences = new ArrayCollection();
        $this->criteresReferentiels = new ArrayCollection();
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

    public function getProgramme()
    {
        $programme =@stream_get_contents($this->programme);
        @fclose($this->programme);
        return base64_encode($programme);
        //return $programme;
    }

    public function setProgramme($programme_pdf): self
    {
        $this->programme=$programme_pdf;

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

    /**
     * @return Collection|CriteresReferentiel[]
     */
    public function getCriteresReferentiels(): Collection
    {
        return $this->criteresReferentiels;
    }

    public function addCriteresReferentiel(CriteresReferentiel $criteresReferentiel): self
    {
        if (!$this->criteresReferentiels->contains($criteresReferentiel)) {
            $this->criteresReferentiels[] = $criteresReferentiel;
            $criteresReferentiel->setReferenceReferentiel($this);
        }

        return $this;
    }

    public function removeCriteresReferentiel(CriteresReferentiel $criteresReferentiel): self
    {
        if ($this->criteresReferentiels->removeElement($criteresReferentiel)) {
            // set the owning side to null (unless already changed)
            if ($criteresReferentiel->getReferenceReferentiel() === $this) {
                $criteresReferentiel->setReferenceReferentiel(null);
            }
        }

        return $this;
    }
}
