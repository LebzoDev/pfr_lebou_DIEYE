<?php

namespace App\Entity;

use App\Entity\Formateur;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupePromoRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=GroupePromoRepository::class)
 */
class GroupePromo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"show_ref_formateur_group","groupe_apprenants","formateur_groupe"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show_ref_formateur_group","groupe_apprenants","formateur_groupe"})
     */
    private $nom;

    /**
     * @ORM\Column(type="date")
     * @Groups({"show_ref_formateur_group","groupe_apprenants","formateur_groupe"})
     */
    private $dateCreation;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="groupePromos")
     * @ORM\JoinColumn(nullable=false)
     * @groups({"groupe_apprenants"})
     */
    private $promo;


    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, inversedBy="mygroupePromos")
     * ApiSubresource()
     * @Groups({"groupe_apprenants"})
     */
    private $apprenants;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"show_ref_formateur_group","groupe_apprenants","formateur_groupe"})
     */
    private $archive;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="groupePromos")
     * @ApiSubresource()
     */
    private $formateurs;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupe_apprenants","formateur_groupe"})
     */
    private $type;

    public function __construct()
    {
        $this->setArchive(false);
        $this->apprenants = new ArrayCollection();
        $this->formateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

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
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        $this->apprenants->removeElement($apprenant);

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
     * @return Collection|Formateur[]
     */
    public function getFormateurs(): Collection
    {
        return $this->formateurs;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateurs->contains($formateur)) {
            $this->formateurs[] = $formateur;
            $formateur->addGroupePromo($this);
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        if ($this->formateurs->removeElement($formateur)) {
            $formateur->removeGroupePromo($this);
        }

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
