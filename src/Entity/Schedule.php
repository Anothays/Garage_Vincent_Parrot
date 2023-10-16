<?php

namespace App\Entity;

use App\Repository\ScheduleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScheduleRepository::class)]
class Schedule
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = 1;

    #[ORM\Column]
    private array $openedDays = [];

    #[ORM\OneToMany(mappedBy: 'schedule', targetEntity: Garage::class)]
    private Collection $garages;

    public function __construct()
    {
        $this->garages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOpenedDays(): array
    {
        return $this->openedDays;
    }

    public function setOpenedDays(array $openedDays): static
    {
        $this->openedDays = $openedDays;

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
            $garage->setSchedule($this);
        }

        return $this;
    }

    public function removeGarage(Garage $garage): static
    {
        if ($this->garages->removeElement($garage)) {
            // set the owning side to null (unless already changed)
            if ($garage->getSchedule() === $this) {
                $garage->setSchedule(null);
            }
        }

        return $this;
    }
}
