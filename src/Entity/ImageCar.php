<?php

namespace App\Entity;

use App\Repository\ImageCarRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageCarRepository::class)]
class ImageCar extends Image
{
    #[ORM\ManyToOne(inversedBy: 'imageCars')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Car $car = null;

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): static
    {
        $this->car = $car;

        return $this;
    }
}
