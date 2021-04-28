<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\ProfilSortieController;
use App\Repository\ProfilSortieRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *    routePrefix="/admin",
 *    attributes={"pagination_items_per_page"=2 },
 *     collectionOperations = {
 *      "get"={
 *          "security"="is_granted('ROLE_ADMINISTRATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_FORMATEUR')",
 *           "security_message"="Vous n'avez pas acces à ce ressource."         
 *      },
 *      "Affiche_ProfilSortie_Promo"={
 *          "name"="Affiche_ProfilSortie_Promo",
 *          "path"="/promo/{id}/profilsorties",
 *          "method"="GET",
 *          "controller"="App\Controller\ProfilSortieController::showAppPromoProfilSortie"
 *       },
 *        "AfficheApprenantProfilSortiePromo"={
 *          "name"="Affiche_Apprenant_ProfilSortie_Promo",
 *          "path"="/promo/{id}/profilsortie/{idp}",
 *          "method"="GET",
 *          "controller"="App\Controller\ProfilSortieController::afficheApprenantsProfilSortiePromo"
 *       },
 *      "post"={
 *           "security"="is_granted('ROLE_ADMINISTRATEUR') or is_granted('ROLE_FORMATEUR')",
 *           "security_message"="Vous n'avez pas acces à ce ressource.",   
 *          },
 *      },
 *      itemOperations = {
 *      "get"={
 *          "security"="is_granted('ROLE_ADMINISTRATEUR') or is_granted('ROLE_FORMATEUR') or  is_granted('ROLE_CM')",
 *          "security_message"="Vous n'avez pas acces à ce ressource.",  
 *      },
 *      "put"={
 *          "security"="is_granted('ROLE_ADMINISTRATEUR') or is_granted('ROLE_FORMATEUR')",
 *          "security_message"="Vous n'avez pas acces à ce ressource.",  
 *      },
 *       "delete"={
 *          "security"="is_granted('ROLE_ADMINISTRATEUR') or is_granted('ROLE_FORMATEUR')",
 *          "security_message"="Vous n'avez pas acces à ce ressource.",  
 *      }
 * })
 *    )
 * @ORM\Entity(repositoryClass=ProfilSortieRepository::class)
 */
class ProfilSortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("affiche")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("affiche")
     */
    private $libelleProfil;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="profilSortie")
     * @ApiSubresource()
     */
    private $apprenants;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $archive;

    public function __construct()
    {
        $this->apprenants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleProfil(): ?string
    {
        return $this->libelleProfil;
    }

    public function setLibelleProfil(string $libelleProfil): self
    {
        $this->libelleProfil = $libelleProfil;

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
            $apprenant->setProfilSortie($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->removeElement($apprenant)) {
            // set the owning side to null (unless already changed)
            if ($apprenant->getProfilSortie() === $this) {
                $apprenant->setProfilSortie(null);
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
}
