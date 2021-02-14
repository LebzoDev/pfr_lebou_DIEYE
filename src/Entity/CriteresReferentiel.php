<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CriteresReferentielRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=CriteresReferentielRepository::class)
 */
class CriteresReferentiel
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
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show_referentiels","formateur_groupe","show_ref_formateur_group","apprenants_attente","show_apprenant_group","referentiel_competence","groupe_apprenants"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archive;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="criteresReferentiels")
     */
    private $referenceReferentiel;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getArchive(): ?bool
    {
        return $this->archive;
    }

    public function setArchive(bool $archive): self
    {
        $this->archive = $archive;

        return $this;
    }

    public function getReferenceReferentiel(): ?Referentiel
    {
        return $this->referenceReferentiel;
    }

    public function setReferenceReferentiel(?Referentiel $referenceReferentiel): self
    {
        $this->referenceReferentiel = $referenceReferentiel;

        return $this;
    }
}
