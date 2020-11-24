<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *  collectionOperations = {
 *      "get"={
 *          "security"="is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') ",
 *          "security_message"="Vous n'avez pas le droit !!!"
 *        },
 *       "post"={
 *          "security"="is_granted('ROLE_FORMATEUR)",
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
     */
    private $profilSortie;

    public function getProfilSortie(): ?ProfilSortie
    {
        return $this->profilSortie;
    }

    public function setProfilSortie(?ProfilSortie $profilSortie): self
    {
        $this->profilSortie = $profilSortie;

        return $this;
    }
}
