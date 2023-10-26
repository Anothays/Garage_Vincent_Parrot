<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    #[
        ORM\Id,
        ORM\GeneratedValue,
        ORM\Column
    ]
    private ?int $id = null;

    #[ORM\Column(length: 9, unique: true)]
    #[Assert\Regex(pattern: "/^[a-zA-Z]{2}-\d{3}-[a-zA-Z]{2}$/", message: "Veuillez renseigner un numéro de plaque valide. exemple : XX-123-WW")]
    private ?string $licensePlate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $registrationDate = null;

    #[ORM\Column]
    private ?int $mileage = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $modifiedAt = null;

    #[ORM\ManyToOne(inversedBy: 'cars')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Garage $garage = null;

    #[ORM\Column(length: 60)]
    private ?string $carEngine = null;

    #[ORM\Column]
    private ?string $carModel = null;

    #[ORM\OneToMany(mappedBy: 'car', targetEntity: ContactMessageCar::class)]
    private Collection $contactMessages;

    #[ORM\Column]
    private ?bool $published = null;

    #[ORM\Column(length: 60)]
    private ?string $carConstructor = null;

    #[ORM\OneToMany(mappedBy: 'car', targetEntity: ImageCar::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $imageCars;

    public function __construct()
    {
        $this->contactMessages = new ArrayCollection();
        $this->imagesCar = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->modifiedAt = new \DateTimeImmutable();
        $this->imageCars = new ArrayCollection();
    }

    public function __toString(): string
    {
        return "{$this->carConstructor} {$this->carModel}";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLicensePlate(): ?string
    {
        return $this->licensePlate;
    }

    public function setLicensePlate(string $licensePlate): static
    {
        $this->licensePlate = $licensePlate;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(\DateTimeInterface $registrationDate): static
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    public function getMileage(): ?int
    {
        return $this->mileage;
    }

    public function setMileage(int $mileage): static
    {
        $this->mileage = $mileage;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeImmutable
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(\DateTimeImmutable $modifiedAt): static
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    public function getGarage(): ?Garage
    {
        return $this->garage;
    }

    public function setGarage(?Garage $garage): static
    {
        $this->garage = $garage;

        return $this;
    }

    public function getCarEngine(): ?string
    {
        return $this->carEngine;
    }

    public function setCarEngine(?string $carEngine): static
    {
        $this->carEngine = $carEngine;

        return $this;
    }

    public function getCarModel(): ?string
    {
        return $this->carModel;
    }

    public function setCarModel(?string $carModel): static
    {
        $this->carModel = $carModel;

        return $this;
    }

    /**
     * @return Collection<int, ContactMessageCar>
     */
    public function getContactMessages(): Collection
    {
        return $this->contactMessages;
    }

    public function addContactMessage(ContactMessageCar $contactMessage): static
    {
        if (!$this->contactMessages->contains($contactMessage)) {
            $this->contactMessages->add($contactMessage);
            $contactMessage->setCar($this);
        }

        return $this;
    }

    public function removeContactMessage(ContactMessageCar $contactMessage): static
    {
        if ($this->contactMessages->removeElement($contactMessage)) {
            // set the owning side to null (unless already changed)
            if ($contactMessage->getCar() === $this) {
                $contactMessage->setCar(null);
            }
        }

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

    public function getCarConstructor(): ?string
    {
        return $this->carConstructor;
    }

    public function setCarConstructor(?string $carConstructor): static
    {
        $this->carConstructor = $carConstructor;
        return $this;
    }

    /**
     * @return Collection<int, ImageCar>
     */
    public function getImageCars(): Collection
    {
        return $this->imageCars;
    }

    public function addImageCar(ImageCar $imageCar): static
    {
        if (!$this->imageCars->contains($imageCar)) {
            $this->imageCars->add($imageCar);
            $imageCar->setCar($this);
        }

        return $this;
    }

    public function removeImageCar(ImageCar $imageCar): static
    {
        if ($this->imageCars->removeElement($imageCar)) {
            // set the owning side to null (unless already changed)
            if ($imageCar->getCar() === $this) {
                $imageCar->setCar(null);
            }
        }

        return $this;
    }
}
