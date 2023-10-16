<?php

namespace App\Entity;

use App\Repository\UserCustomerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserCustomerRepository::class)]
class UserCustomer extends User
{
    #[ORM\Column]
    private ?bool $isVerified = false;

    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}