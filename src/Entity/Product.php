<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    collectionOperations: ['GET', 'POST'],
    itemOperations: ['GET'],
    normalizationContext: ['groups' => ['product']]
)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[
        Assert\Type(
            type: 'string',
            message: 'Votre titre doit être de type {{ type }}.',
        ),
        Assert\Length(
            min: 5,
            max: 255,
            minMessage: 'Le titre doit contenir au moins 3 caractères !',
            maxMessage: 'Le titre ne peut contenir plus de 255 caractères !'
        ),
        Assert\NotNull(
            message: 'La valeur ne doit pas être NULL',
        ),
        Assert\NotBlank(
            message: 'Votre champ est vide.',
            normalizer: 'trim',
        ),
    ]
    #[Groups(['brand', 'category', 'command', 'product', 'productPicture', 'review'])]
    private $label;

    #[ORM\Column(type: 'text')]
    #[
        Assert\Type(
            type: 'string',
            message: 'Votre titre doit être de type {{ type }}.',
        ),
        Assert\Length(
            min: 5,
            max: 255,
            minMessage: 'Le titre doit contenir au moins 3 caractères !',
            maxMessage: 'Le titre ne peut contenir plus de 255 caractères !'
        ),
        Assert\NotNull(
            message: 'La valeur ne doit pas être NULL',
        ),
        Assert\NotBlank(
            message: 'Votre champ est vide.',
            normalizer: 'trim',
        ),

    ]
    #[Groups(['brand', 'category', 'command', 'product', 'productPicture', 'review'])]
    private $description;

    #[ORM\Column(type: 'integer')]
    #[
        Assert\Type(
            type: 'integer',
            message: 'Le prix doit être de type {{ type }}.',
        ),
        Assert\NotNull(
            message: 'La valeur ne doit pas être NULL',
        ),
        Assert\NotBlank(
            message: 'Votre champ est vide.',
            normalizer: 'trim',
        ),
        Assert\Positive(
            message: 'La valeur doit etre positive et supérieur a 0 !'
        ),
    ]
    #[Groups(['brand', 'category', 'command', 'product', 'productPicture', 'review'])]
    private $price;

    #[ORM\Column(type: 'integer')]
    #[
        Assert\Type(
            type: 'integer',
            message: 'Le stock doit être de type {{ type }}.',
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
    #[Groups(['brand', 'category', 'command', 'product', 'productPicture', 'review'])]
    private $stock;

    #[ORM\Column(type: 'boolean')]
    #[
        Assert\Type(
            type: 'bool',
            message: 'Le champ doit être de type {{ type }}.',
        ),
        Assert\NotNull(
            message: 'La valeur ne doit pas être NULL',
        ),
        Assert\NotBlank(
            message: 'Votre champ est vide.',
            normalizer: 'trim',
        ),

    ]
    #[Groups(['brand', 'category', 'command', 'product', 'productPicture', 'review'])]
    private $isActif;

    #[ORM\ManyToMany(targetEntity: Command::class, mappedBy: 'products')]
    #[Groups(['product'])]
    private $commands;

    #[ORM\ManyToOne(targetEntity: Brand::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['product'])]
    private $brand;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Review::class)]
    #[Groups(['product'])]
    private $reviews;

    //Losque l'on supprime un produit, on veut également supprimer son image on utilise donc cascade: ['remove'])
    //Si on veut l'ajouter on utilise persist cascade: ['persist'])]
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductPicture::class, cascade: ['remove','persist'])]
    #[Groups(['product'])]
    private $productPictures;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'products')]
    #[Groups(['product'])]
    private $categories;

    public function __construct()
    {
        $this->commands = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->productPictures = new ArrayCollection();
        $this->categories = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getIsActif(): ?bool
    {
        return $this->isActif;
    }

    public function setIsActif(bool $isActif): self
    {
        $this->isActif = $isActif;

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
            $command->addProduct($this);
        }

        return $this;
    }

    public function removeCommand(Command $command): self
    {
        if ($this->commands->removeElement($command)) {
            $command->removeProduct($this);
        }

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

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
            $review->setProduct($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getProduct() === $this) {
                $review->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProductPicture>
     */
    public function getProductPictures(): Collection
    {
        return $this->productPictures;
    }

    public function addProductPicture(ProductPicture $productPicture): self
    {
        if (!$this->productPictures->contains($productPicture)) {
            $this->productPictures[] = $productPicture;
            $productPicture->setProduct($this);
        }

        return $this;
    }

    public function removeProductPicture(ProductPicture $productPicture): self
    {
        if ($this->productPictures->removeElement($productPicture)) {
            // set the owning side to null (unless already changed)
            if ($productPicture->getProduct() === $this) {
                $productPicture->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }
}
