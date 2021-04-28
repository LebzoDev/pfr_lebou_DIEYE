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
 *             "controller"="App\Controller\PromoController::admin_promo",
 *             "path"="/promo"
 *          },
 *          "admin_promo_principal"={
 *             "security"="is_granted('ROLE_ADMINISTRATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_FORMATEUR')",
 *             "security_message"="Vous n'avez pas acces à ce service",
 *             "path"="/principal",
 *             "methods"="GET",
 *             "controller"="App\Controller\PromoController::admin_promo_principal"
 *          },
 *          "apprenants_attente"={
 *             "security"="is_granted('ROLE_ADMINISTRATEUR') or is_granted('ROLE_FORMATEUR')",
 *             "security_message"="Vous n'avez pas acces à ce service",
 *             "path"="admin/promo/apprenants/attente",
 *             "methods"="GET",
 *             "controller"="App\Controller\PromoController::apprenants_attente",
 *             "normalization_context"={"groups"={"apprenants_attente"}}
 *          },
 *        "post"={
 *             "security"="is_granted('ROLE_ADMINISTRATEUR') or is_granted('ROLE_FORMATEUR')",
 *             "security_message"="Vous n'avez pas acces à ce service",
 *             "controller"="App\Controller\PromoController::post::promo",
 *             "path"="/promo"
 *          }
 *      },
 *      itemOperations={
 *           "get"={
 *             "security"="is_granted('ROLE_ADMINISTRATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_FORMATEUR')",
 *             "security_message"="Vous n'avez pas acces à ce service",
 *             "normalization_context"={"groups"={"show_ref_formateur_group"}},
 *             "controller"="App\Controller\PromoController::admin_promo_item",
 *             "path"="/promo/{id}"
 *          },
 *          "admin_promo_principal"={
 *             "security"="is_granted('ROLE_ADMINISTRATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_FORMATEUR')",
 *             "security_message"="Vous n'avez pas acces à ce service",
 *             "path"="/{id}/principal",
 *             "methods"="GET",
 *             "controller"="App\Controller\PromoController::admin_promo_principal_item"
 *          },
 *           "apprenants_attente"={
 *             "security"="is_granted('ROLE_ADMINISTRATEUR') or is_granted('ROLE_FORMATEUR')",
 *             "security_message"="Vous n'avez pas acces à ce service",
 *             "path"="admin/promo/{id}/apprenants/attente",
 *             "methods"="GET",
 *             "controller"="App\Controller\PromoController::apprenants_attente_item",
 *             "normalization_context"={"groups"={"apprenants_attente"}}
 *          },  
 *            "referentiel_competence"={
 *                  "security"="is_granted('ROLE_ADMINISTRATEUR') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')",
 *                  "security_message"="Vous n'avez pas acces à ce service",
 *                  "path"="/promo/{id}/referentiel/",
 *                  "methods"="GET",
 *                  "controller"="App\Controller\PromoController::referentiel_competence",
 *          },
 *            "groupe_apprenants"={
 *                  "security"="is_granted('ROLE_ADMINISTRATEUR') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')",
 *                  "security_message"="Vous n'avez pas acces à ce service",
 *                  "path"="/promo/{id}/groupes/{idgroupe}/apprenants",
 *                  "methods"="GET",
 *                  "controller"="App\Controller\PromoController::groupe_apprenants",
 *          },
 *            "formateur_groupe"={
 *                  "security"="is_granted('ROLE_ADMINISTRATEUR') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')",
 *                  "security_message"="Vous n'avez pas acces à ce service",
 *                  "path"="/promo/{id}/formateurs",
 *                  "methods"="GET",
 *                  "controller"="App\Controller\PromoController::formateur_groupe",
 *          },
 *          "put","delete"
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
     * @Groups({"show_ref_formateur_group","show_apprenant_group","apprenants_attente","referentiel_competence","groupe_apprenants"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show_ref_formateur_group","show_apprenant_group","apprenants_attente","groupe_apprenants"})
     */
    private $lieu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"show_ref_formateur_group","show_apprenant_group","apprenants_attente","referentiel_competence","groupe_apprenants"})
     */
    private $referenceAgate;

    /**
     * @ORM\Column(type="date")
     * @Groups({"show_ref_formateur_group","show_apprenant_group","apprenants_attente","referentiel_competence","groupe_apprenants"})
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     * @Groups({"show_ref_formateur_group","show_apprenant_group","apprenants_attente","referentiel_competence","groupe_apprenants"})
     */
    private $dateFin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"show_ref_formateur_group","show_apprenant_group","apprenants_attente","referentiel_competence","groupe_apprenants"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show_ref_formateur_group","show_apprenant_group","apprenants_attente","referentiel_competence","groupe_apprenants"})
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"apprenants_attente","referentiel_competence","groupe_apprenants"})
     */
    private $titre;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="promo")
     * @Groups({"show_ref_formateur_group","show_apprenant_group","apprenants_attente"})
     * @ApiSubresource()
     */
    private $apprenants;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"show_ref_formateur_group","show_apprenant_group","apprenants_attente","referentiel_competence","groupe_apprenants"})
     */
    private $archive;

    /**
     * @ORM\OneToMany(targetEntity=GroupePromo::class, mappedBy="promo")
     * @ApiSubresource()
     * @Groups({"show_ref_formateur_group","formateur_groupe"})
     */
    private $groupePromos;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"show_ref_formateur_group","show_apprenant_group","apprenants_attente","referentiel_competence","groupe_apprenants"})
     */
    private $avatar;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="promos")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"show_apprenant_group","show_ref_formateur_group","apprenants_attente","referentiel_competence","groupe_apprenants","formateur_groupe"})
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
