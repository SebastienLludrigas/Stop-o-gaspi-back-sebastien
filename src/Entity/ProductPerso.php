<?php

namespace App\Entity;

use App\Repository\ProductPersoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProductPersoRepository::class)
 */
class ProductPerso
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("apiv0")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups("apiv0")
     */
    private $name;

    /**
     * @ORM\Column(type="date")
     * @Groups("apiv0")
     */
    private $expiration_date;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups("apiv0")
     */
    private $elaboration_date;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("apiv0")
     */
    private $ingredients;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups("apiv0")
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("apiv0")
     */
    private $nutritional_composition;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups("apiv0")
     */
    private $favorite;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups("apiv0")
     */
    private $archived;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups("apiv0")
     */
    private $expirated;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("apiv0")
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Groups("apiv0")
     */
    private $barcode;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="productPerso", fetch="EAGER")
     * 
     */
    private $user;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups("apiv0")
     */
    private $archived_date;

   
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("apiv0")
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=55, nullable=true)
     * @Groups("apiv0")
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     * @Groups("apiv0")
     */
    private $nutriscore_grade;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("apiv0")
     */
    private $product_quantity;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $Timestamp;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups("apiv0")
     */
    private $expires_in;

   

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getExpirationDate(): ?\DateTimeInterface
    {
        return $this->expiration_date;
    }

    public function setExpirationDate(\DateTimeInterface $expiration_date): self
    {
        $this->expiration_date = $expiration_date;

        return $this;
    }

    public function getElaborationDate(): ?\DateTimeInterface
    {
        return $this->elaboration_date;
    }

    public function setElaborationDate(?\DateTimeInterface $elaboration_date): self
    {
        $this->elaboration_date = $elaboration_date;

        return $this;
    }

    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    public function setIngredients(?string $ingredients): self
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getNutritionalComposition(): ?string
    {
        return $this->nutritional_composition;
    }

    public function setNutritionalComposition(?string $nutritional_composition): self
    {
        $this->nutritional_composition = $nutritional_composition;

        return $this;
    }

    public function getFavorite(): ?bool
    {
        return $this->favorite;
    }

    public function setFavorite(?bool $favorite): self
    {
        $this->favorite = $favorite;

        return $this;
    }

    public function getArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(?bool $archived): self
    {
        $this->archived = $archived;

        return $this;
    }

    public function getExpirated(): ?bool
    {
        return $this->expirated;
    }

    public function setExpirated(?bool $expirated): self
    {
        $this->expirated = $expirated;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(?string $barcode): self
    {
        $this->barcode = $barcode;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getArchivedDate(): ?\DateTimeInterface
    {
        return $this->archived_date;
    }

    public function setArchivedDate(?\DateTimeInterface $archived_date): self
    {
        $this->archived_date = $archived_date;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getNutriscoreGrade(): ?string
    {
        return $this->nutriscore_grade;
    }

    public function setNutriscoreGrade(?string $nutriscore_grade): self
    {
        $this->nutriscore_grade = $nutriscore_grade;

        return $this;
    }

    public function getProductQuantity(): ?string
    {
        return $this->product_quantity;
    }

    public function setProductQuantity(?string $product_quantity): self
    {
        $this->product_quantity = $product_quantity;

        return $this;
    }

    public function getTimestamp(): ?string
    {
        return $this->Timestamp;
    }

    public function setTimestamp(?string $Timestamp): self
    {
        $this->Timestamp = $Timestamp;

        return $this;
    }

    public function getExpiresIn(): ?int
    {
        return $this->expires_in;
    }

    public function setExpiresIn(?int $expires_in): self
    {
        $this->expires_in = $expires_in;

        return $this;
    }



   

  
}
