<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Table(name="`user`")
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("apiv0")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups("apiv0")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups("apiv0")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups("apiv0")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups("apiv0")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups("apiv0")
     */
    private $city;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("apiv0")
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity=ProductPerso::class, mappedBy="user", orphanRemoval=true)
     * @Groups("apiv0")
     */
    private $productPerso;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups("apiv0")
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("apiv0")
     */
    private $username;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups("apiv0")
     */
    private $alert_day;

    public function __construct()
    {
        $this->productPerso = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

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
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->email,
            $this->roles,
            $this->name,
            $this->city,
            $this->created_at,
            $this->productPerso,
            $this->pseudo,
            $this->alert_day

        ));
    }
     /** @see \Serializable::unserialize() */
     public function unserialize($serialized)
     {
         list (
            $this->id,
            $this->username,
            $this->password,
            $this->email,
            $this->roles,
            $this->name,
            $this->city,
            $this->created_at,
            $this->productPerso,
            $this->pseudo,
            $this->alert_day
         ) = unserialize($serialized);
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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

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

    /**
     * @return Collection|ProductPerso[]
     */
    public function getProductPerso(): Collection
    {
        return $this->productPerso;
    }

    public function addProductPerso(ProductPerso $productPerso): self
    {
        if (!$this->productPerso->contains($productPerso)) {
            $this->productPerso[] = $productPerso;
            $productPerso->setUser($this);
        }

        return $this;
    }

    public function removeProductPerso(ProductPerso $productPerso): self
    {
        if ($this->productPerso->contains($productPerso)) {
            $this->productPerso->removeElement($productPerso);
            // set the owning side to null (unless already changed)
            if ($productPerso->getUser() === $this) {
                $productPerso->setUser(null);
            }
        }

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getAlertDay(): ?int
    {
        return $this->alert_day;
    }

    public function setAlertDay(?int $alert_day): self
    {
        $this->alert_day = $alert_day;

        return $this;
    }
}
