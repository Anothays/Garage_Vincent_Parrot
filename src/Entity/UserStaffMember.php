<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Repository\UserStaffMemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserStaffMemberRepository::class)]
class UserStaffMember extends User
{

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Garage $garage = null;

    #[ORM\OneToMany(mappedBy: 'approvedBy', targetEntity: TestimonialApproved::class)]
    private Collection $approvedTestimonials;

    public function __construct()
    {
        $this->approvedTestimonials = new ArrayCollection();
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

    /**
     * @return Collection<int, Testimonial>
     */
    public function getApprovedTestimonials(): Collection
    {
        return $this->approvedTestimonials;
    }

    public function addApprovedTestimonial(TestimonialApproved $approvedTestimonial): static
    {
        if (!$this->approvedTestimonials->contains($approvedTestimonial)) {
            $this->approvedTestimonials->add($approvedTestimonial);
            $approvedTestimonial->setApprovedBy($this);
        }

        return $this;
    }

    public function removeApprovedTestimonial(TestimonialApproved $approvedTestimonial): static
    {
        if ($this->approvedTestimonials->removeElement($approvedTestimonial)) {
            // set the owning side to null (unless already changed)
            if ($approvedTestimonial->getApprovedBy() === $this) {
                $approvedTestimonial->setApprovedBy(null);
            }
        }
        return $this;
    }

}