<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormateurRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *      collectionOperations = {
 *          "get"={
 *              "security"="is_granted('ROLE_CM')",
 *              "security_message"="VOUS N'AVEZ PAS ACCES À CE SERVICE"
 *        },
 *  },
 * itemOperations = {
 *      "get"={
 *          "security"="is_granted('view', object) and object",
 *          "security_message"="VOUS N'AVEZ PAS ACCES À CE SERVICE"
 *        },
 *      "put"={
 *          "security"="is_granted('edit', object) and object",
 *          "security_message"="VOUS N'AVEZ PAS ACCES À CE SERVICE"
 * },
 * })
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 */
class Formateur extends User
{
    /**
     * @ORM\OneToMany(targetEntity=GroupePromo::class, mappedBy="formateurs")
     */
    private $groupePromos;

    public function __construct()
    {
        $this->groupePromos = new ArrayCollection();
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
            $groupePromo->addFormateur($this);
        }

        return $this;
    }

    public function removeGroupePromo(GroupePromo $groupePromo): self
    {
        if ($this->groupePromos->removeElement($groupePromo)) {
            // set the owning side to null (unless already changed)
                $groupePromo->removeFormateur($this);
        }

        return $this;
    }
}
