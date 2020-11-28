<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CompetenceRepository;
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
 *      collectionOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_ADMINISTRATEUR') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')",
 *              "security_message"="vous n'avez pas acces à ce resource"
 *           },
 *          "post"={
 *              "security"="is_granted('ROLE_ADMINISTRATEUR')",
 *              "security_message"="vous n'avez pas acces à ce resource"
 *           },     
 *      },
 *      itemOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_ADMINISTRATEUR') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')",
 *              "security_message"="vous n'avez pas acces à ce resource"
 *           },
 *          "put"={
 *              "security"="is_granted('ROLE_ADMINISTRATEUR')",
 *              "security_message"="vous n'avez pas acces à ce resource"
 *           }
 *      }
 * )
 * @ORM\Entity(repositoryClass=CompetenceRepository::class)
 * @ApiFilter(BooleanFilter::class, properties={"archive"})
 */
class Competence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("show_grpcompetences")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("show_grpcompetences")
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("show_grpcompetences")
     */
    private $descriptif;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("show_cgrpompetences")
     */
    private $archive;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetences::class, inversedBy="competences")
     * @ApiSubresource()
     */
    private $groupcompetences;

    /**
     * @ORM\OneToMany(targetEntity=Niveau::class, mappedBy="competence", orphanRemoval=true)
     */
    private $niveaux;

    public function __construct()
    {
        $this->archive = false;
        $this->groupcompetences = new ArrayCollection();
        $this->niveaux = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
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
     * @return Collection|GroupeCompetences[]
     */
    public function getGroupcompetences(): Collection
    {
        return $this->groupcompetences;
    }

    public function addGroupcompetence(GroupeCompetences $groupcompetence): self
    {
        if (!$this->groupcompetences->contains($groupcompetence)) {
            $this->groupcompetences[] = $groupcompetence;
        }

        return $this;
    }

    public function removeGroupcompetence(GroupeCompetences $groupcompetence): self
    {
        $this->groupcompetences->removeElement($groupcompetence);

        return $this;
    }

    /**
     * @return Collection|Niveau[]
     */
    public function getNiveaux(): Collection
    {
        return $this->niveaux;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->niveaux->contains($niveau)) {
            $this->niveaux[] = $niveau;
            $niveau->setCompetence($this);
        }
        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        if ($this->niveaux->removeElement($niveau)) {
            // set the owning side to null (unless already changed)
            if ($niveau->getCompetence() === $this) {
                $niveau->setCompetence(null);
            }
        }

        return $this;
    }
}
