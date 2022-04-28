<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\NewCustomerController;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    collectionOperations: [
        'GET', 'POST',
        'get_new_customer' => [
            'method' => 'GET',
            'path' => '/user/get_new_customer',
            'controller' => NewCustomerController::class
        ],
    ],
    itemOperations: ['GET'],
    normalizationContext: ['groups' => ['user']]
)]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[
        Assert\Email(

            message: 'Votre email doit être de type {{ type }}.',
        ),
        Assert\NotNull(
            message: 'La valeur ne doit pas être NULL',
        ),
        Assert\NotBlank(
            message: 'Votre champ est vide.',
            normalizer: 'trim',
        ),

    ]
    #[Groups(['address', 'command', 'review', 'user'])]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    #[
        Assert\Type(
            type: 'string',
            message: 'Votre prénom doit être de type {{ type }}.',
        ),
        Assert\Length(
            min: 5,
            max: 255,
            minMessage: 'Le prénom doit contenir au moins 3 caractères !',
            maxMessage: 'Le prénom ne peut contenir plus de 255 caractères !'
        ),
        Assert\NotNull(
            message: 'La valeur ne doit pas être NULL',
        ),
        Assert\NotBlank(
            message: 'Votre champ est vide.',
            normalizer: 'trim',
        ),

    ]
    #[Groups(['address', 'command', 'review', 'user'])]
    private $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    #[
        Assert\Type(
            type: 'string',
            message: 'Votre nom doit être de type {{ type }}.',
        ),
        Assert\Length(
            min: 5,
            max: 255,
            minMessage: 'Le nom doit contenir au moins 3 caractères !',
            maxMessage: 'Le nom ne peut contenir plus de 255 caractères !'
        ),
        Assert\NotNull(
            message: 'La valeur ne doit pas être NULL',
        ),
        Assert\NotBlank(
            message: 'Votre champ est vide.',
            normalizer: 'trim',
        ),

    ]
    #[Groups(['address', 'command', 'review', 'user'])]
    private $lastName;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Address::class)]
    #[Groups(['user'])]
    private $addresses;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Review::class)]
    #[Groups(['user'])]
    private $reviews;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Command::class)]
    #[Groups(['user'])]
    private $commands;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['user'])]
    private $createdAt;



    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->commands = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }


    /**
     * @return Collection<int, Address>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setUser($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getUser() === $this) {
                $address->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setUser($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUser() === $this) {
                $review->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Command>
     */
    public function getCommands(): Collection
    {
        return $this->commands;
    }

    public function addCommand(Command $command): self
    {
        if (!$this->commands->contains($command)) {
            $this->commands[] = $command;
            $command->setUser($this);
        }

        return $this;
    }

    public function removeCommand(Command $command): self
    {
        if ($this->commands->removeElement($command)) {
            // set the owning side to null (unless already changed)
            if ($command->getUser() === $this) {
                $command->setUser(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
