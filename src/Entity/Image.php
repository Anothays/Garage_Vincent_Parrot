<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\MappedSuperclass]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $filename = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $alt = null;


//    #[ORM\ManyToOne(inversedBy: 'images')]
//    #[ORM\JoinColumn(nullable: false, onDelete: 'cascade')]
//    private ?Car $car = null;


    public function __toString(): string
    {
        return $this->filename || 'none';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): static
    {
        $this->alt = $alt;

        return $this;
    }

//    public function getCar(): ?Car
//    {
//        return $this->car;
//    }
//
//    public function setCar(?Car $car): static
//    {
//        $this->car = $car;
//
//        return $this;
//    }
}
