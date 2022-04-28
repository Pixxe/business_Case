<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use App\Repository\CommandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\AverageBasketController;
use App\Controller\CommandBasketController;
use App\Controller\CommandConversionController;
use App\Controller\CommandCountController;
use App\Controller\CommandTotalAmountController;
use App\Controller\ConversionBasketController;
use App\Controller\RecurrenceCommandController;

#[ORM\Entity(repositoryClass: CommandRepository::class)]
#[ApiResource(
    collectionOperations: [
        'GET', 'POST',
        'get_total_command_from_dates' => [
            'method' => 'GET',
            'path' => '/command/get_total_command',
            'controller' =>  CommandCountController::class
        ],
        'get_total_amount' => [
            'method' => 'GET',
            'path' => '/command/get_total_amount_commands',
            'controller' => CommandTotalAmountController::class
        ],
        'get_basket' => [
            'method' => 'GET',
            'path' => 'command/get_basket',
            'controller' => CommandBasketController::class
        ],
        'get_average_basket' => [
            'method' => 'GET',
            'path' => '/command/get_average_basket',
            'controller' => AverageBasketController::class
        ],
        'get_percent_conversion_basket' => [
            'method' => 'GET',
            'path' => 'command/get_percent_conversion_basket',
            'controller' => ConversionBasketController::class
        ],
        'get_percent_command_conversion' => [
            'method' => 'GET',
            'path' => '/command/get_percent_command_conversion',
            'controller' => CommandConversionController::class
        ],
        'get_recurrence_command_client_from_dates' => [
            'method' => 'GET',
            'path' => '/command/get_recurrence_command_client_from_dates',
            'controller' => RecurrenceCommandController::class
        ]

    ],
    itemOperations: ['GET'],
    normalizationContext: ['groups' => ['command']]
)]
#[ApiFilter(OrderFilter::class, properties: ['CreatedAt'])]
#[ApiFilter(SearchFilter::class, properties: ['command.user' => 'exact'])]
#[ApiFilter(DateFilter::class, properties: ['CreatedAt'])]
class Command
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    #[
        Assert\Positive(
            message: 'La valeur doit etre positive et supérieur a 0 !'
        ),
    ]
    #[Groups(['address', 'command', 'product', 'user'])]
    private $totalPrice;

    #[ORM\Column(type: 'integer')]
    #[
        Assert\Type(
            type: 'string',
            message: 'Votre numéro de commande doit être de type {{ type }}.',
        ),
        Assert\Length(
            min: 5,
            max: 255,
            minMessage: 'Votre numéro de commande doit contenir au moins 5 caractères !',
            maxMessage: 'Votre numéro de commande doit contenir plus de 255 caractères !'
        ),
        Assert\NotNull(
            message: 'La valeur ne doit pas être NULL',
        ),
        Assert\NotBlank(
            message: 'Votre champ est vide.',
            normalizer: 'trim',
        ),
    ]
    #[Groups(['address', 'command', 'product', 'user'])]
    private $numCommand;

    #[ORM\Column(type: 'datetime')]
    #[
        Assert\Type(
            type: 'datetime',
            message: 'Votre date doit être de type {{ type }}.',
        ),
        Assert\DateTime(
            message: 'Le format de la date doit être de type Y-m-d',
        ),
        Assert\NotNull(
            message: 'La valeur ne doit pas être NULL',
        ),
        Assert\NotBlank(
            message: 'Votre champ est vide.',
            normalizer: 'trim',
        ),
    ]
    #[Groups(['address', 'command', 'product', 'user'])]
    private $CreatedAt;

    #[ORM\Column(type: 'integer')]
    #[
        Assert\Type(
            type: 'integer',
            message: 'Le status doit être de type {{ type }}.',
        ),
    ]
    #[Groups(['command', 'product', 'user'])]
    private $status;

    #[ORM\ManyToOne(targetEntity: Address::class, inversedBy: 'commands')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['command'])]
    private $address;

    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: 'commands')]
    #[Groups(['command'])]
    private $products;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commands')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['command'])]
    private $user;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getNumCommand(): ?int
    {
        return $this->numCommand;
    }

    public function setNumCommand(int $numCommand): self
    {
        $this->numCommand = $numCommand;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeInterface $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        $this->products->removeElement($product);

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
}
