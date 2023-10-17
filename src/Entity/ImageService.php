<?php

namespace App\Entity;

use App\Repository\ImageServiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageServiceRepository::class)]
class ImageService extends Image
{
    #[ORM\ManyToOne(cascade: ['persist', 'remove'], inversedBy: 'imageServices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Service $service = null;

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): static
    {
        $this->service = $service;

        return $this;
    }
}
