<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BrandsRepository")
 */
class Brands
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $brand_name;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rating;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="brands")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reviews", mappedBy="brand_id", orphanRemoval=true)
     */
    private $reviews=0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $totalStars=0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $totalReviews=0;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Category;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $SubCategory;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $SubCategory2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $SubCategory3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $SubCategory4;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $SubCategory5;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $wepagelink;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebooklink;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $twitterlink;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $instagramlink;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrandName(): ?string
    {
        return $this->brand_name;
    }

    public function setBrandName(string $brand_name): self
    {
        $this->brand_name = $brand_name;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(): self
    {
        $this->rating = $this->totalStars/$this->totalReviews;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return Collection|Reviews[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Reviews $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setBrandId($this);
        }

        return $this;
    }

    public function removeReview(Reviews $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getBrandId() === $this) {
                $review->setBrandId(null);
            }
        }

        return $this;
    }

    public function getTotalStars(): ?int
    {
        return $this->totalStars;
    }

    public function setTotalStars(?int $totalStars): self
    {
        $this->totalStars = $totalStars;

        return $this;
    }

    public function getTotalReviews(): ?int
    {
        return $this->totalReviews;
    }

    public function setTotalReviews(?int $totalReviews): self
    {
        $this->totalReviews = $totalReviews;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->Category;
    }

    public function setCategory(?string $Category): self
    {
        $this->Category = $Category;
        
        return $this;
    }

    public function getSubCategory(): ?string
    {
        return $this->SubCategory;
    }

    public function setSubCategory(?string $SubCategory): self
    {
        $this->SubCategory = $SubCategory;

        return $this;
    }

    public function getSubCategory2(): ?string
    {
        return $this->SubCategory2;
    }

    public function setSubCategory2(?string $SubCategory2): self
    {
        $this->SubCategory2 = $SubCategory2;

        return $this;
    }

    public function getSubCategory3(): ?string
    {
        return $this->SubCategory3;
    }

    public function setSubCategory3(?string $SubCategory3): self
    {
        $this->SubCategory3 = $SubCategory3;

        return $this;
    }

    public function getSubCategory4(): ?string
    {
        return $this->SubCategory4;
    }

    public function setSubCategory4(?string $SubCategory4): self
    {
        $this->SubCategory4 = $SubCategory4;

        return $this;
    }

    public function getSubCategory5(): ?string
    {
        return $this->SubCategory5;
    }

    public function setSubCategory5(?string $SubCategory5): self
    {
        $this->SubCategory5 = $SubCategory5;

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

    public function getWepagelink(): ?string
    {
        return $this->wepagelink;
    }

    public function setWepagelink(?string $wepagelink): self
    {
        $this->wepagelink = $wepagelink;

        return $this;
    }

    public function getFacebooklink(): ?string
    {
        return $this->facebooklink;
    }

    public function setFacebooklink(?string $facebooklink): self
    {
        $this->facebooklink = $facebooklink;

        return $this;
    }

    public function getTwitterlink(): ?string
    {
        return $this->twitterlink;
    }

    public function setTwitterlink(?string $twitterlink): self
    {
        $this->twitterlink = $twitterlink;

        return $this;
    }

    public function getInstagramlink(): ?string
    {
        return $this->instagramlink;
    }

    public function setInstagramlink(?string $instagramlink): self
    {
        $this->instagramlink = $instagramlink;

        return $this;
    }
}
