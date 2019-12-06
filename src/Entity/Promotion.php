<?php

declare(strict_types=1);

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PromotionRepository")
 */
class Promotion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="promotion_type")
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $priority;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isGeneral;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PromotionOption", mappedBy="promotion")
     */
    private $promotionOptions;

    public function __construct()
    {
        $this->promotionOptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection|PromotionOption[]
     */
    public function getPromotionOptions(): Collection
    {
        return $this->promotionOptions;
    }

    public function addPromotionOption(PromotionOption $promotionOption): self
    {
        if (!$this->promotionOptions->contains($promotionOption)) {
            $this->promotionOptions[] = $promotionOption;
            $promotionOption->setPromotion($this);
        }

        return $this;
    }

    public function removePromotionOption(PromotionOption $promotionOption): self
    {
        if ($this->promotionOptions->contains($promotionOption)) {
            $this->promotionOptions->removeElement($promotionOption);
            // set the owning side to null (unless already changed)
            if ($promotionOption->getPromotion() === $this) {
                $promotionOption->setPromotion(null);
            }
        }

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getIsGeneral(): ?bool
    {
        return $this->isGeneral;
    }

    public function setIsGeneral(bool $isGeneral): self
    {
        $this->isGeneral = $isGeneral;

        return $this;
    }

}
