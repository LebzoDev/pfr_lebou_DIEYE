<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 */
class Apprenant extends User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $profilId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProofilId(): ?int
    {
        return $this->proofilId;
    }

    public function setProofilId(?int $proofilId): self
    {
        $this->proofilId = $proofilId;

        return $this;
    }
}
