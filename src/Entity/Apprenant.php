<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *  collectionOperations = {
 *      "get"={
 *          "security"="is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') ",
 *          "security_message"="Vous n'avez pas le droit !!!"
 *        },
 *       "post"={
 *          "security"="is_granted('ROLE_FORMATEUR')",
 *          "security_message"="Vous n'avez pas le droit !!!"
 *       }
 *  },
 * itemOperations = {
 *      "get"={
 *          "security"="is_granted('APP_VIEW', object) and object",
 *          "security_message"="Vous n'avez pas le droit"
 *        },
 *      "put"={
 *          "security"="is_granted('APP_EDIT', object) and object",
 *          "security_message"="Vous n'avez pas le droit"
 *      },
 *      "delete"={
 *          "security"="is_granted('APP_DELETE', object) and object",
 *          "security_message"="Vous n'avez pas le droit"
 *      },
 * })
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 */
class Apprenant extends User
{
    /**
     * @ORM\ManyToOne(targetEntity=ProfilSortie::class, inversedBy="apprenants")
     * @Groups("affiche")
     */
    private $profilSortie;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="apprenants")
     * @Groups("affiche")
     */
    private $promo;

    /**
     * @ORM\ManyToMany(targetEntity=GroupePromo::class, mappedBy="apprenants")
     */
    private $mygroupePromos;

    public function __construct()
    {
        $this->mygroupePromos = new ArrayCollection();
    }

    public function getProfilSortie(): ?ProfilSortie
    {
        return $this->profilSortie;
    }

    public function setProfilSortie(?ProfilSortie $profilSortie): self
    {
        $this->profilSortie = $profilSortie;

        return $this;
    }

    public function getPromo(): ?Promo
    {
        return $this->promo;
    }

    public function setPromo(?Promo $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

    /**
     * @return Collection|GroupePromo[]
     */
    public function getMygroupePromos(): Collection
    {
        return $this->mygroupePromos;
    }

    public function addMygroupePromo(GroupePromo $mygroupePromo): self
    {
        if (!$this->mygroupePromos->contains($mygroupePromo)) {
            $this->mygroupePromos[] = $mygroupePromo;
            $mygroupePromo->addApprenant($this);
        }

        return $this;
    }

    public function removeMygroupePromo(GroupePromo $mygroupePromo): self
    {
        if ($this->mygroupePromos->removeElement($mygroupePromo)) {
            $mygroupePromo->removeApprenant($this);
        }

        return $this;
    }
}
