<?php

namespace App\Entity;

use App\Repository\SoapRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SoapRepository::class)]
class Soap
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $benefits = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $precautions = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $utilization = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $effect = null;

    #[ORM\Column(name: 'skin_type', length: 255, nullable: true)]
    private ?string $skinType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $action = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $usageTime = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, Rating>
     */
    #[ORM\OneToMany(targetEntity: Rating::class, mappedBy: 'soap', orphanRemoval: true)]
    private Collection $ratings;

    /**
     * @var Collection<int, Favorite>
     */
    #[ORM\OneToMany(targetEntity: Favorite::class, mappedBy: 'soap', orphanRemoval: true)]
    private Collection $favorites;

    /**
     * @var Collection<int, Media>
     */
    #[ORM\OneToMany(targetEntity: Media::class, mappedBy: 'soap', orphanRemoval: true)]
    private Collection $media;

    public function __construct()
    {
        $this->ratings = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBenefits(): ?string
    {
        return $this->benefits;
    }

    public function setBenefits(?string $benefits): static
    {
        $this->benefits = $benefits;

        return $this;
    }

    public function getPrecautions(): ?string
    {
        return $this->precautions;
    }

    public function setPrecautions(?string $precautions): static
    {
        $this->precautions = $precautions;

        return $this;
    }

    public function getUtilization(): ?string
    {
        return $this->utilization;
    }

    public function setUtilization(?string $utilization): static
    {
        $this->utilization = $utilization;

        return $this;
    }

    public function getEffect(): ?string
    {
        return $this->effect;
    }

    public function setEffect(?string $effect): static
    {
        $this->effect = $effect;

        return $this;
    }

    public function getSkinType(): ?string
    {
        return $this->skinType;
    }

    public function setSkinType(?string $skinType): static
    {
        $this->skinType = $skinType;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(?string $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function getUsageTime(): ?string
    {
        return $this->usageTime;
    }

    public function setUsageTime(?string $usageTime): static
    {
        $this->usageTime = $usageTime;

        return $this;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): static
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setSoap($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): static
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getSoap() === $this) {
                $rating->setSoap(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Favorite>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorite $favorite): static
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites->add($favorite);
            $favorite->setSoap($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): static
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getSoap() === $this) {
                $favorite->setSoap(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): static
    {
        if (!$this->media->contains($medium)) {
            $this->media->add($medium);
            $medium->setSoap($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): static
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getSoap() === $this) {
                $medium->setSoap(null);
            }
        }

        return $this;
    }
}
