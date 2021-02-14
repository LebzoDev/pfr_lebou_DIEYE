<?php

namespace App\Entity;

use App\Entity\Profil;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\UserController;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\InheritanceType;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"user" = "User", "apprenant" = "Apprenant","formateur"="Formateur", "cm"="CM"})

 * @ApiResource(
 *    routePrefix="/admin",
 *    attributes={"pagination_items_per_page"=25 },
 *    normalizationContext={"groups"={"show_users"}},
 *    collectionOperations = {
 *      "get",
 *      "post"={
 *          "path"="/users",
 *          "controller"="App\Controller\UserController::addUser",
 *          "deserialize"=false,
 *          },
 *  },
 * itemOperations = {
 *      "get",
 *      "put"={
 *            "deserialize"=false,
 *            "path"="/users/{id}",
 *            "controller"="App\Controller\UserController::putUser"
 *       },
 *      "delete"
 * })
 * @ApiFilter(BooleanFilter::class, properties={"archive"})
 * @ApiFilter(SearchFilter::class, properties={"profil"="exact"})
 * 
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"show_ref_formateur_group","show_users","show_apprenant_group","apprenants_attente"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     */
    protected $username;

    /**
    * @Groups({"show_profils","show_apprenant_group","apprenants_attente"})
    * @Groups("show_users")
    * @Groups("affiche")
    */
    protected $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    protected $password;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"show_users","affiche","show_apprenant_group","apprenants_attente"})
     */

    protected $profil;
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show_users","show_users_profils","show_ref_formateur_group","affiche","show_apprenant_group","apprenants_attente"})
     */
    protected $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("show_users_profils")
     * @Groups("show_users")
     * @Assert\NotBlank
     * @Groups("affiche")
     * @Groups({"show_ref_formateur_group","show_apprenant_group","apprenants_attente"})
     */
    protected $nom;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"show_users","affiche","show_ref_formateur_group","show_apprenant_group","apprenants_attente"})
     */
    protected $photo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Email(
     *      message = "The email '{{ value }}' is not a valid email.")
     * @Assert\Regex("/^[a-zA-Z0-9]*@[a-z]{2,}\.[a-z]{3}$/")
     * @Groups("affiche")
     * @Groups({"affiche","show_users","show_ref_formateur_group","show_apprenant_group","apprenants_attente"})
     */

    protected $email;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"show_users","affiche","show_ref_formateur_group","show_apprenant_group","apprenants_attente"})
     */
    protected $archive;
    

    public function __construct(){
        $this->archive=false;
    }
  
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->profil->getLibelle();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPhoto()
    {
        $photo =@stream_get_contents($this->photo);
        @fclose($this->photo);
        return base64_encode($photo);
    }

    public function setPhoto($photo): self
    {
        $this->photo=$photo;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

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
