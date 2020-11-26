<?php

namespace App\Entity;

use App\Entity\User;
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
}
