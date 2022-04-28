<?php

namespace App\Entity;

use App\Repository\ProductPictureRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductPictureRepository::class)]
#[ApiResource(
    collectionOperations: ['GET', 'POST'],
    itemOperations: ['GET'],
    normalizationContext: ['groups' => ['productPicture']]
)]
class ProductPicture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]

    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[
        Assert\Type(
            type: 'string',
            message: 'Votre url doit être de type {{ type }}.',
        ),

        Assert\NotNull(
            message: 'La valeur ne doit pas être NULL',
        ),
        Assert\NotBlank(
            message: 'Votre champ est vide.',
            normalizer: 'trim',
        ),

    ]
    #[Groups(['product','productPicture'])]
    private $path;

    #[ORM\Column(type: 'string', length: 255)]
    #[
        Assert\Type(
            type: 'string',
            message: 'Votre libele doit être de type {{ type }}.',
        ),
        Assert\Length(
            min: 5,
            max: 255,
            minMessage: 'Le libele doit contenir au moins 3 caractères !',
            maxMessage: 'Le libele ne peut contenir plus de 255 caractères !'
        ),
        Assert\NotNull(
            message: 'La valeur ne doit pas être NULL',
        ),
        Assert\NotBlank(
            message: 'Votre champ est vide.',
            normalizer: 'trim',
        ),

    ]
    #[Groups(['product','productPicture'])]
    private $libele;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'productPictures')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['productPicture'])]
    private $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getLibele(): ?string
    {
        return $this->libele;
    }

    public function setLibele(string $libele): self
    {
        $this->libele = $libele;

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
