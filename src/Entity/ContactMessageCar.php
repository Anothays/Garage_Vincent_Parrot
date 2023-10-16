<?php

namespace App\Entity;

use App\Repository\ContactMessageCarRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactMessageCarRepository::class)]
class ContactMessageCar
{
    #[ORM\OneToOne(inversedBy: 'concernedCar')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'cascade')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private ?ContactMessage $contactMessage = null;

    #[ORM\ManyToOne(inversedBy: 'contactMessages')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'cascade')]
    private ?Car $car = null;

    public function __toString(): string
    {
        return "{$this->car->getCarConstructor()} {$this->car->getCarModel()}";
    }

    public function getContactMessage(): ?ContactMessage
    {
        return $this->contactMessage;
    }

    public function setContactMessage(ContactMessage $contactMessage): static
    {
        $this->contactMessage = $contactMessage;

        return $this;
    }

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
