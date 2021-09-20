<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\SetOperation;
use App\Controller\SetUniqueOperation;
use App\Repository\SetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     formats={"json"={"application/json"}},
 *     normalizationContext={"groups"={"setget"}},
 *     collectionOperations={
 *         "gets" = {
 *              "method" = "GET",
 *              "path" = "/sets",
 *              "controller" = SetOperation::class,
 *              "security"="is_granted('ROLE_USER')",
 *              "normalization_context"={"groups"={"setgets"}}
 *         }
 *     },
 *     itemOperations={
 *         "get" = {
 *              "method" = "GET",
 *               "path" = "/sets/{id}",
 *              "controller" = SetUniqueOperation::class,
 *              "security"="is_granted('ROLE_USER')",
 *         }
 *     }
 * )
 * @ORM\Entity(repositoryClass=SetRepository::class)
 * @ORM\Table(name="`set`")
 */
class Set
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setgets")
     * @Groups("setget")
     * @Groups("setgeta")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setgets")
     * @Groups("setget")
     */
    private $abbreviatedName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setgets")
     * @Groups("setget")
     */
    private $name;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setgets")
     * @Groups("setget")
     */
    private $releaseDate;

    /**
     * @ORM\ManyToOne(targetEntity=SetType::class, inversedBy="sets")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setgets")
     * @Groups("setget")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Country::class, inversedBy="sets")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setgets")
     * @Groups("setget")
     */
    private $country;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $src;

    /**
     * @ORM\OneToMany(targetEntity=Card::class, mappedBy="relatedSet")
     * @Groups("setgets")
     * @Groups("setget")
     */
    private $cards;

    /**
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setgets")
     * @Groups("setget")
     */
    private $count;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isValid;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAbbreviatedName(): ?string
    {
        return $this->abbreviatedName;
    }

    public function setAbbreviatedName(string $abbreviatedName): self
    {
        $this->abbreviatedName = $abbreviatedName;

        return $this;
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

    public function getSrc(): ?string
    {
        return $this->src;
    }

    public function setSrc(?string $src): self
    {
        $this->src = $src;

        return $this;
    }

    public function getType(): ?SetType
    {
        return $this->type;
    }

    public function setType(?SetType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function setCards($cards): self
    {
        $this->cards = $cards;
        return $this;
    }

    /**
     * @return Collection|Card[]
     */
    public function getCards(): Collection
    {
        $this->count = count($this->cards);
        $this->cards= new ArrayCollection($this->cards->getValues());
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setRelatedSet($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->removeElement($card)) {
            // set the owning side to null (unless already changed)
            if ($card->getRelatedSet() === $this) {
                $card->setRelatedSet(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getIsValid(): ?bool
    {
        return $this->isValid;
    }

    public function setIsValid(bool $isValid): self
    {
        $this->isValid = $isValid;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }
}
