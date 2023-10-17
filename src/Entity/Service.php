<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?bool $published = null;

    #[ORM\ManyToMany(targetEntity: Garage::class, inversedBy: 'services')]
    private Collection $garages;

//    #[ORM\OneToOne(mappedBy: 'service', targetEntity: ImageService::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
//    private ?ImageService $imageService = null;

    #[ORM\OneToMany(mappedBy: 'service', targetEntity: ImageService::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $imageServices;

    /**
     * @return Collection<int, ImageService>
     */
    public function getImageServices(): Collection
    {
        return $this->imageServices;
    }

    public function addImageService(ImageService $imageService): static
    {
        if (!$this->imageServices->contains($imageService)) {
            $this->imageServices->add($imageService);
            $imageService->setService($this);
        }

        return $this;
    }

    public function removeImageService(ImageService $imageService): static
    {
        if ($this->imageServices->removeElement($imageService)) {
            // set the owning side to null (unless already changed)
            if ($imageService->getService() === $this) {
                $imageService->setService(null);
            }
        }

        return $this;
    }

    public function __construct()
    {
        $this->garages = new ArrayCollection();
        $this->imageServices = new ArrayCollection();
    }

    public function __toString(): string
    {
        return "{$this->name}";
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): static
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return Collection<int, Garage>
     */
    public function getGarages(): Collection
    {
        return $this->garages;
    }

    public function addGarage(Garage $garage): static
    {
        if (!$this->garages->contains($garage)) {
            $this->garages->add($garage);
        }

        return $this;
    }

    public function removeGarage(Garage $garage): static
    {
        $this->garages->removeElement($garage);

        return $this;
    }

//    public function getImageService(): ?ImageService
//    {
//        return $this->imageService;
//    }
//
//    public function setImageService(ImageService $imageService): static
//    {
//        // set the owning side of the relation if necessary
//        if ($imageService->getService() !== $this) {
//            $imageService->setService($this);
//        }
//
//        $this->imageService = $imageService;
//
//        return $this;
//    }
}
