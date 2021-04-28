<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Elasticsearch\DataProvider\Filter\MatchFilter;

/**
 * @ApiResource(
 *  collectionOperations = {
 *      "get"={
 *          "security"="is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') ",
 *          "security_message"="Vous n'avez pas le droit !!!"
 *        },
 *       "post"={
 *          "deserialize"=false,
 *          "security"="is_granted('ROLE_ADMINISTRATEUR')",
 *          "security_message"="Vous n'avez pas le droit !!!",
 *          "path"="/apprenants",
 *          "controller"="App\Controller\UserController::addApprenant"
 *       }
 *  },
 * itemOperations = {
 *      "get"={
 *          "security"="is_granted('APP_VIEW', object) and object",
 *          "security_message"="Vous n'avez pas le droit"
 *        },
 *      "put"={
 *          "security"="is_granted('APP_EDIT', object) and object",
 *          "security_message"="Vous n'avez pas le droit",
 *          "deserialize"=false
 *      },
 *      "active_apprenant"={
 *          "security"="is_granted('APP_EDIT', object) and object",
 *          "security_message"="Vous n'avez pas le droit",
 *           "method"="put",
 *          "deserialize"=false,
 *          "path"="/apprenants_active/{id}",
 *          "controller"="App\Controller\UserController::activeApprenant"
 *       },
 *       "relance_apprenant"={
 *          "security"="is_granted('APP_EDIT', object) and object",
 *          "security_message"="Vous n'avez pas le droit",
 *           "method"="put",
 *          "deserialize"=false,
 *          "path"="/apprenants_relance/{id}",
 *          "controller"="App\Controller\UserController::activeApprenant"
 *       },
 *      "delete"={
 *          "security"="is_granted('APP_DELETE', object) and object",
 *          "security_message"="Vous n'avez pas le droit"
 *      },
 * })
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 *
 */
class Apprenant extends User
{
    /**
     * @ORM\ManyToOne(targetEntity=ProfilSortie::class, inversedBy="apprenants")
     * @Groups({"affiche","apprenants_attente"})
     */
    private $profilSortie;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="apprenants")
     * @Groups({"affiche","apprenants_attente"})
     */
    private $promo;

    /**
     * @ORM\ManyToMany(targetEntity=GroupePromo::class, mappedBy="apprenants")
     */
    private $mygroupePromos;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"affiche","apprenants_attente"})
     */
    private $status;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
