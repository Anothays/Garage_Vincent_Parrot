<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

//#[ORM\Entity(repositoryClass: UserRepository::class)]
//#[ORM\InheritanceType('TABLE_PER_CLASS')]
//#[ORM\DiscriminatorColumn(name: 'discr', type: 'string')]
//#[ORM\DiscriminatorMap(['staff' => UserStaffMember::class, 'customer' => UserCustomer::class])]
#[ORM\MappedSuperclass]
#[UniqueEntity(fields: ['email'], message: 'Un compte existe déjà avec cet email')]
abstract class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $firstname = null;

    #[ORM\Column(length: 180)]
    private ?string $lastname = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(length: 60)]
    #[
        Assert\Regex(
        pattern: "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/",
        message: 'Le mot de passe doit contenir au minimum 12 caractères dont une minuscule, 
        une majuscule, un chiffre, un caractère spéciale',
        ),
        Assert\NotBlank(['message' => 'Le mot de passe de peut pas être vide'])
    ]
    private ?string $password = null;

//    #[ORM\OneToMany(mappedBy: 'approvedBy', targetEntity: TestimonialApproved::class)]
//    private Collection $approvedTestimonials;

//    #[ORM\ManyToOne(inversedBy: 'users')]
//    private ?Garage $garage = null;

//    #[ORM\Column]
//    private ?bool $isVerified = false;


    public function __toString(): string
    {
        return htmlspecialchars("{$this->firstname} {$this->lastname}");
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFullname(): string
    {
        return htmlspecialchars($this->firstname . " " . $this->lastname);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

//    /**
//     * @return Collection<int, Testimonial>
//     */
//    public function getApprovedTestimonials(): Collection
//    {
//        return $this->approvedTestimonials;
//    }
//
//    public function addApprovedTestimonial(TestimonialApproved $approvedTestimonial): static
//    {
//        if (!$this->approvedTestimonials->contains($approvedTestimonial)) {
//            $this->approvedTestimonials->add($approvedTestimonial);
//            $approvedTestimonial->setApprovedBy($this);
//        }
//
//        return $this;
//    }
//
//    public function removeApprovedTestimonial(TestimonialApproved $approvedTestimonial): static
//    {
//        if ($this->approvedTestimonials->removeElement($approvedTestimonial)) {
//            // set the owning side to null (unless already changed)
//            if ($approvedTestimonial->getApprovedBy() === $this) {
//                $approvedTestimonial->setApprovedBy(null);
//            }
//        }
//        return $this;
//    }


//    public function getGarage(): ?Garage
//    {
//        return $this->garage;
//    }
//
//    public function setGarage(?Garage $garage): static
//    {
//        $this->garage = $garage;
//
//        return $this;
//    }

//    public function getIsVerified(): ?bool
//    {
//        return $this->isVerified;
//    }
//
//    public function setIsVerified(bool $isVerified): static
//    {
//        $this->isVerified = $isVerified;
//
//        return $this;
//    }


}
