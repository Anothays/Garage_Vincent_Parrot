<?php

namespace App\Entity;

use App\Repository\TestimonialApprovedRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestimonialApprovedRepository::class)]
class TestimonialApproved
{
    #[ORM\OneToOne(inversedBy: 'approval')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'cascade')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "NONE")]
    private ?Testimonial $testimonial = null;

    #[ORM\ManyToOne(inversedBy: 'approvedTestimonials')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'set null')]
    private ?UserStaffMember $approvedBy = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $approvedAt;

    public function __construct()
    {
        $this->approvedAt = new \DateTimeImmutable();
    }

    public function __toString(): string
    {
        return "{$this->approvedBy->getFullname()} le {$this->approvedAt->format('d/m/y à H:i')}";
    }

    public function getTestimonial(): ?Testimonial
    {
        return $this->testimonial;
    }

    public function setTestimonial(Testimonial $testimonial): static
    {
        $this->testimonial = $testimonial;

        return $this;
    }

    public function getApprovedBy(): ?User
    {
        return $this->approvedBy;
    }

    public function setApprovedBy(?User $user): static
    {
        $this->approvedBy = $user;

        return $this;
    }

    public function getApprovedAt(): ?\DateTimeImmutable
    {
        return $this->approvedAt;
    }

    public function setApprovedAt(\DateTimeImmutable $approvedAt): static
    {
        $this->approvedAt = $approvedAt;

        return $this;
    }
}
