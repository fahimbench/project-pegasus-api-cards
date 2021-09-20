<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\CardOperation;
use App\Controller\CardUniqueOperation;
use App\Repository\CardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;


/**
 * @ApiResource(
 *     formats={"json"={"application/json"}},
 *     normalizationContext={"groups"={"cardget"}},
 *     collectionOperations={
 *         "gets" = {
 *              "method" = "GET",
 *              "path" = "/cards",
 *              "controller" = CardOperation::class,
 *              "security"="is_granted('ROLE_USER')",
 *              "normalization_context"={"groups"={"cardgets"}}
 *         },
 *     },
 *     itemOperations={
 *         "get" = {"security"="is_granted('ROLE_USER')"}
 *     }
 * )
 * @ORM\Entity(repositoryClass=CardRepository::class)
 */
class Card
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setget")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setget")
     */
    private $isMonster = 0;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setget")
     */
    private $isTrap = 0;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setget")
     */
    private $isMagic = 0;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setget")
     */
    private $name;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setget")
     */
    private $attack;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setget")
     */
    private $defense;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setget")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setget")
     */
    private $idCard;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setget")
     */
    private $level;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setget")
     */
    private $pendulumScale;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setget")
     */
    private $pendulumDesc;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setget")
     */
    private $linkLevel;

    /**
     * @ORM\ManyToOne(targetEntity=Attribute::class, inversedBy="cards")
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setget")
     */
    private $attribute;

    /**
     * @ORM\ManyToMany(targetEntity=MonsterType::class, inversedBy="cards")
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setget")
     */
    private $typeMonster;

    /**
     * @ORM\ManyToMany(targetEntity=CardType::class, inversedBy="cards")
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setget")
     */
    private $cardType;


    /**
     * @ORM\ManyToOne(targetEntity=Icone::class, inversedBy="cards")
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setget")
     */
    private $icone;

    /**
     * @ORM\ManyToOne(targetEntity=Rarity::class, inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setget")
     */
    private $rarity;

    /**
     * @ORM\ManyToOne(targetEntity=Set::class, inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("cardgets")
     * @Groups("cardget")
     */
    private $relatedSet;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("cardgets")
     * @Groups("cardget")
     * @Groups("setget")
     */
    private $src;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isValid;

    public function __construct()
    {
        $this->cardType = new ArrayCollection();
        $this->typeMonster = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttack(): ?int
    {
        return $this->attack;
    }

    public function setAttack(int $attack): self
    {
        $this->attack = $attack;

        return $this;
    }

    public function getDefense(): ?int
    {
        return $this->defense;
    }

    public function setDefense(int $defense): self
    {
        $this->defense = $defense;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getIdCard(): ?string
    {
        return $this->idCard;
    }

    public function setIdCard(string $idCard): self
    {
        $this->idCard = $idCard;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getPendulumScale(): ?int
    {
        return $this->pendulumScale;
    }

    public function setPendulumScale(?int $pendulumScale): self
    {
        $this->pendulumScale = $pendulumScale;

        return $this;
    }

    public function getPendulumDesc(): ?string
    {
        return $this->pendulumDesc;
    }

    public function setPendulumDesc(?string $pendulumDesc): self
    {
        $this->pendulumDesc = $pendulumDesc;

        return $this;
    }

    public function getIsMonster(): ?bool
    {
        return $this->isMonster;
    }

    public function setIsMonster(bool $isMonster): self
    {
        $this->isMonster = $isMonster;

        return $this;
    }

    public function getSrc(): ?string
    {
        return $this->url()."image/".$this->getId();
    }

    public function setSrc(?string $src): self
    {
        $this->src = $src;

        return $this;
    }

    public function getAttribute(): ?Attribute
    {
        return $this->attribute;
    }

    public function setAttribute(?Attribute $attribute): self
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * @return Collection|CardType[]
     */
    public function getCardType(): Collection
    {
        return $this->cardType;
    }

    public function addCardType(CardType $cardType): self
    {
        if (!$this->cardType->contains($cardType)) {
            $this->cardType[] = $cardType;
        }

        return $this;
    }

    public function removeCardType(CardType $cardType): self
    {
        $this->cardType->removeElement($cardType);

        return $this;
    }

    public function getIcone(): ?Icone
    {
        return $this->icone;
    }

    public function setIcone(?Icone $icone): self
    {
        $this->icone = $icone;

        return $this;
    }

    public function getRarity(): ?Rarity
    {
        return $this->rarity;
    }

    public function setRarity(?Rarity $rarity): self
    {
        $this->rarity = $rarity;

        return $this;
    }

    public function getRelatedSet(): ?Set
    {
        return $this->relatedSet;
    }

    public function setRelatedSet(?Set $relatedSet): self
    {
        $this->relatedSet = $relatedSet;

        return $this;
    }

    /**
     * @return Collection|MonsterType[]
     */
    public function getTypeMonster(): Collection
    {
        return $this->typeMonster;
    }

    public function addTypeMonster(MonsterType $typeMonster): self
    {
        if (!$this->typeMonster->contains($typeMonster)) {
            $this->typeMonster[] = $typeMonster;
        }

        return $this;
    }

    public function removeTypeMonster(MonsterType $typeMonster): self
    {
        $this->typeMonster->removeElement($typeMonster);

        return $this;
    }

    public function getIsTrap(): ?bool
    {
        return $this->isTrap;
    }

    public function setIsTrap(?bool $isTrap): self
    {
        $this->isTrap = $isTrap;

        return $this;
    }

    public function getIsMagic(): ?bool
    {
        return $this->isMagic;
    }

    public function setIsMagic(?bool $isMagic): self
    {
        $this->isMagic = $isMagic;

        return $this;
    }

    public function getLinkLevel(): ?int
    {
        return $this->linkLevel;
    }

    public function setLinkLevel(?int $linkLevel): self
    {
        $this->linkLevel = $linkLevel;

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

    public function getImg(): ?string
    {
        return $this->src;
    }

    function url(){
        $hostName = $_SERVER['HTTP_HOST'];
        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';

        return $protocol.'://'.$hostName."/";
    }
}
