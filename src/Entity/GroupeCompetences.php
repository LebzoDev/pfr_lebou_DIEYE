<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\GroupeCompetencesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ApiResource(
 *     routePrefix="/admin",
 *     collectionOperations={
 *          "get"={
 *               "security"="is_granted('ROLE_ADMINISTRATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_FORMATEUR')",
 *               "security_message"="Vous n'avez pas acces à cette ressource",
 *               "normalization_context"={"groups"={"show_grpcompetences"}},
 *          },
 *          "post"={
 *              "security"="is_granted('ROLE_ADMINISTRATEUR')",
 *               "security_message"="Vous n'avez pas acces à cette ressource",
 *               "controller"="App\Controller\GroupeCompetencesController::addGroupCompetence",
 *          }
 *      },
 *      itemOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_ADMINISTRATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_FORMATEUR')",
 *               "security_message"="Vous n'avez pas acces à cette ressource",
 *               "normalization_context"={"groups"={"show_grpcompetences"}},
 *          },
 *          "put"={
 *              "security"="is_granted('ROLE_ADMINISTRATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_FORMATEUR')",
 *               "security_message"="Vous n'avez pas acces à cette ressource",
 *               "controller"="App\Controller\GroupeCompetencesController::putGroupController"
 *          }
 *      }
 * )
 * @ORM\Entity(repositoryClass=GroupeCompetencesRepository::class)
 *  @ApiFilter(BooleanFilter::class, properties={"archive"})
 * @ApiFilter(SearchFilter::class,properties={"libelle":"exact"})
 */
class GroupeCompetences
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"show_grpcompetences","show_referentiels"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show_grpcompetences","show_referentiels"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show_grpcompetences","show_referentiels"})
     */
    private $descriptif;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"show_grpcompetences","show_referentiels"})
     */
    private $archive;

    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, mappedBy="groupcompetences")
     * @Groups("show_grpcompetences")
     * @ApiSubresource()
     */
    private $competences;

    /**
     * @ORM\ManyToMany(targetEntity=Referentiel::class, mappedBy="groupeCompetences")
     */
    private $referentiels;

    public function __construct()
    {
        $this->archive = false;
        $this->competences = new ArrayCollection();
        $this->referentiels = new ArrayCollection();
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

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

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
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
            $competence->addGroupcompetence($this);
        }
        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        if ($this->competences->removeElement($competence)) {
            $competence->removeGroupcompetence($this);
        }
        return $this;
    }

    /**
     * @return Collection|Referentiel[]
     */
    public function getReferentiels(): Collection
    {
        return $this->referentiels;
    }

    public function addReferentiel(Referentiel $referentiel): self
    {
        if (!$this->referentiels->contains($referentiel)) {
            $this->referentiels[] = $referentiel;
            $referentiel->addGroupeCompetence($this);
        }

        return $this;
    }

    public function removeReferentiel(Referentiel $referentiel): self
    {
        if ($this->referentiels->removeElement($referentiel)) {
            $referentiel->removeGroupeCompetence($this);
        }

        return $this;
    }

   
}
