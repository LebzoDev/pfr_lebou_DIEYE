<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PromoRepository;
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
 *             "security"="is_granted('ROLE_ADMINISTRATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_FORMATEUR')",
 *             "security_message"="Vous n'avez pas acces à ce service",
 *             "normalization_context"={"groups"={"show_ref_formateur_group"}},
 *             "controller"="App\Controller\PromoController::admin_promo"
 *          },
 *          "admin_promo_principal"={
 *             "security"="is_granted('ROLE_ADMINISTRATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_FORMATEUR')",
 *             "security_message"="Vous n'avez pas acces à ce service",
 *             "path"="/principal",
 *             "methods"="GET",
 *             "controller"="App\COntroller\PromoController::admin_promo_principal"
 *          },
 *          "apprenants_attente"={
 *             "security"="is_granted('ROLE_ADMINISTRATEUR') or is_granted('ROLE_FORMATEUR')",
 *             "security_message"="Vous n'avez pas acces à ce service",
 *             "path"="promo/apprenants/attente",
 *             "methods"="GET",
 *             "controller"="App\COntroller\PromoController::apprenants_attente"
 *          },"post"
 *      },
 *      itemOperations={
 *          "get","put","delete"
 *      })
 * @ORM\Entity(repositoryClass=PromoRepository::class)
 * @ApiFilter(BooleanFilter::class, properties={"archive"})
 */
class Promo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"show_ref_formateur_group"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lieu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $referenceAgate;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     */
    private $dateFin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="promo")
     * @ApiSubresource()
     */
    private $apprenants;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $archive;

    /**
     * @ORM\OneToMany(targetEntity=GroupePromo::class, mappedBy="promo")
     * @ApiSubresource()
     * @Groups({"show_ref_formateur_group"})
     */
    private $groupePromos;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $avatar;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="promos")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"show_ref_formateur_group"})
     */
    private $referentiel;

    public function __construct()
    {
        $this->archive = false;
        $this->apprenants = new ArrayCollection();
        $this->groupePromos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getReferenceAgate(): ?string
    {
        return $this->referenceAgate;
    }

    public function setReferenceAgate(?string $referenceAgate): self
    {
        $this->referenceAgate = $referenceAgate;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
            $apprenant->setPromo($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->removeElement($apprenant)) {
            // set the owning side to null (unless already changed)
            if ($apprenant->getPromo() === $this) {
                $apprenant->setPromo(null);
            }
        }

        return $this;
    }

    public function getArchive(): ?bool
    {
        return $this->archive;
    }

    public function setArchive(?bool $archive): self
    {
        $this->archive = $archive;

        return $this;
    }

    /**
     * @return Collection|GroupePromo[]
     */
    public function getGroupePromos(): Collection
    {
        return $this->groupePromos;
    }

    public function addGroupePromo(GroupePromo $groupePromo): self
    {
        if (!$this->groupePromos->contains($groupePromo)) {
            $this->groupePromos[] = $groupePromo;
            $groupePromo->setPromo($this);
        }

        return $this;
    }

    public function removeGroupePromo(GroupePromo $groupePromo): self
    {
        if ($this->groupePromos->removeElement($groupePromo)) {
            // set the owning side to null (unless already changed)
            if ($groupePromo->getPromo() === $this) {
                $groupePromo->setPromo(null);
            }
        }

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getReferentiel(): ?Referentiel
    {
        return $this->referentiel;
    }

    public function setReferentiel(?Referentiel $referentiel): self
    {
        $this->referentiel = $referentiel;

        return $this;
    }

  
}
