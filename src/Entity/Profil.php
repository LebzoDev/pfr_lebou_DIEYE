<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\ProfilController;
use App\Repository\ProfilRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * attributes={"pagination_items_per_page"=2},
 * collectionOperations = {
 *      "get","post",
 *      "get_role_admin"={
 *      "method"="GET",
 *      "path"="/admin/profils",
 *      "controller"="App\Controller\ProfilController::get_profils"
 *      },
 *      "get_post_admin"={
 *      "method"="POST",
 *      "path"="/admin/profils",
 *      "controller"="App\Controller\ProfilController::post_profils"
 *      },
 *      "get_list_user_profil"={
 *      "method"="GET",
 *      "path"="/admin/profils/{id}/users",
 *      "controller"="App\Controller\ProfilController::get_list_users_profils"
 *      },
 *  },
 * itemOperations = {
 *      "get","put","patch",
 *      "get_profil_id"={
 *      "method"="GET",
 *      "path"="/admin/profils/{id}",
 *      "controller"="App\Controller\ProfilController::get_profil"
 *      },
 *      "put_profil_id"={
 *      "method"="PUT",
 *      "path"="/admin/profils/{id}",
 *      "controller"="App\Controller\ProfilController::put_profil"
 *      },
 *       "archive_profil"={
 *      "method"="PUT",
 *      "path"="/admin/profils/{id}/archive",
 *      "controller"="App\Controller\ProfilController",
 *      "controller"="App\Controller\ProfilController::archive_profil"
 *      }
 * })
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 */
class Profil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("show_profils")
     * @Groups("show_users_profils")
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups("show_profils")
     * @Groups("show_users_profils")
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="profil")
     * @Groups("show_users_profils")
     * @ApiSubresource()
     */
    private $users;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups("show_profils")
     * @Groups("show_users_profils")
     */
    private $archive;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setProfil($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getProfil() === $this) {
                $user->setProfil(null);
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
