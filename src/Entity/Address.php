<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ApiResource(
    collectionOperations: ['GET', 'POST'],
    itemOperations: ['GET'],
    normalizationContext: ['groups' => ['address']]
)]

class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    #[
        Assert\Type(
            type: 'integer',
            message: 'Votre message doit être de type {{ type }}.',
        ),
        Assert\NotBlank(
            message: 'Votre champ est vide.',
            normalizer: 'trim',
        ),
        Assert\Positive(
            message: 'La valeur doit etre positive et supérieur a 0 !'
        ),
    ]

    #[Groups(['address', 'city', 'command', 'user'])]
    private $streetNumber;

    #[ORM\Column(type: 'string', length: 255)]
    #[
        Assert\Type(
            type: 'string',
            message: 'Votre message doit être de type {{ type }}.',
        ),
        Assert\Length(
            min: 5,
            max: 255,
            minMessage: 'Votre adresse doit contenir au moins 5 caractères !',
            maxMessage: 'Votre adresse ne peut contenir plus de 155 caractères !'
        ),
        Assert\NotNull(
            message: 'La valeur ne doit pas être NULL',
        ),
        Assert\NotBlank(
            message: 'Votre champ est vide.',
            normalizer: 'trim',
        ),
    ]
    #[Groups(['address', 'city', 'command', 'user'])]
    private $streetName;

    #[ORM\OneToMany(mappedBy: 'address', targetEntity: Command::class)]
    #[Groups(['address'])]
    private $commands;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'addresses')]
    #[Groups(['address'])]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: City::class, inversedBy: 'addresses')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['address'])]
    private $city;


    public function __construct()
    {
        $this->commands = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreetNumber(): ?int
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(int $streetNumber): self
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    public function getStreetName(): ?string
    {
        return $this->streetName;
    }

    public function setStreetName(string $streetName): self
    {
        $this->streetName = $streetName;

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
            $command->setAddress($this);
        }

        return $this;
    }

    public function removeCommand(Command $command): self
    {
        if ($this->commands->removeElement($command)) {
            // set the owning side to null (unless already changed)
            if ($command->getAddress() === $this) {
                $command->setAddress(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }
}
