<?php

namespace App\Entity;

use App\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BrandRepository::class)]
#[ApiResource(
    collectionOperations: ['GET', 'POST'],
    itemOperations: ['GET'],
    normalizationContext: ['groups' => ['brand']]
)]
class Brand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[
        Assert\Type(
            type: 'string',
            message: 'Votre message doit être de type {{ type }}.',
        ),
        Assert\Length(
            min: 5,
            max: 255,
            minMessage: 'La marque doit contenir au moins 5 caractères !',
            maxMessage: 'La marque ne peut contenir plus de 255 caractères !'
        ),
        Assert\NotNull(
            message: 'La valeur ne doit pas être NULL',
        ),
        Assert\NotBlank(
            message: 'Votre champ est vide.',
            normalizer: 'trim',
        ),
    ]
    #[Groups(['brand', 'product'])]
    private $label;

    #[ORM\Column(type: 'string', length: 255)]
    #[
        Assert\Type(
            type: 'string',
            message: 'Votre url doit être de type {{ type }}.',
        ),
        Assert\Length(
            min: 5,
            max: 255,
            minMessage: 'Votre url doit contenir au moins 5 caractères !',
            maxMessage: 'Votre url ne peut contenir plus de 255 caractères !'
        ),
        Assert\NotNull(
            message: 'La valeur ne doit pas être NULL',
        ),
        Assert\NotBlank(
            message: 'Votre champ est vide.',
            normalizer: 'trim',
        ),
    ]
    #[Groups(['brand', 'product'])]
    private $imagePath;

    #[ORM\OneToMany(mappedBy: 'brand', targetEntity: Product::class)]
    #[Groups(['brand'])]
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): self
    {
        $this->imagePath = $imagePath;

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
            $product->setBrand($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getBrand() === $this) {
                $product->setBrand(null);
            }
        }

        return $this;
    }
}
