<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[ApiResource(
    collectionOperations: ['GET', 'POST'],
    itemOperations: ['GET'],
    normalizationContext: ['groups' => ['review']]
)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    #[
        Assert\Type(
            type: 'integer',
            message: 'La note doit être de type {{ type }}.',
        ),
        Assert\NotNull(
            message: 'La valeur ne doit pas être NULL',
        ),
        Assert\NotBlank(
            message: 'Votre champ est vide.',
            normalizer: 'trim',
        ),
        Assert\PositiveOrZero(
            message: 'La valeur doit etre positive ou égale à 0 !'
        ),
    ]
    #[Groups(['product', 'review', 'user'])]
    private $note;

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
    #[Groups(['product', 'review', 'user'])]
    private $createdAt;

    #[ORM\Column(type: 'text')]
    #[
        Assert\Type(
            type: 'string',
            message: 'Votre contenu doit être de type {{ type }}.',
        ),
        Assert\Length(
            min: 5,
            max: 255,
            minMessage: 'Le contenu doit contenir au moins 3 caractères !',
            maxMessage: 'Le contenu ne peut contenir plus de 255 caractères !'
        ),
        Assert\NotNull(
            message: 'La valeur ne doit pas être NULL',
        ),
        Assert\NotBlank(
            message: 'Votre champ est vide.',
            normalizer: 'trim',
        ),

    ]
    #[Groups(['product', 'review', 'user'])]
    private $content;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['review'])]
    private $user;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['review'])]
    private $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
