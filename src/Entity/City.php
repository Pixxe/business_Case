<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;



#[ORM\Entity(repositoryClass: CityRepository::class)]

#[ApiResource(
    collectionOperations: ['GET', 'POST'],
    itemOperations: ['GET'],
    normalizationContext: ['groups' => ['city']]
)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial'])]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[
        Assert\Type(
            type: 'string',
            message: 'Le nom de la ville doit être de type {{ type }}.',
        ),
        Assert\Length(
            min: 5,
            max: 255,
            minMessage: 'Le nom de la ville doit contenir au moins 3 caractères !',
            maxMessage: 'Le nom de la ville ne peut contenir plus de 255 caractères !'
        ),
        Assert\NotNull(
            message: 'La valeur ne doit pas être NULL',
        ),
        Assert\NotBlank(
            message: 'Votre champ est vide.',
            normalizer: 'trim',
        ),
    ]
    #[Groups(['address', 'city'])]
    private $name;

    #[ORM\Column(type: 'integer')]
    #[
        Assert\Type(
            type: 'integer',
            message: 'Votre code postale doit être de type {{ type }}.',
        ),
        Assert\NotBlank(
            message: 'Votre champ est vide.',
            normalizer: 'trim',
        ),
        Assert\Positive(
            message: 'La valeur doit etre positive et supérieur a 0 !'
        ),
    ]
    #[Groups(['address', 'city'])]
    private $cp;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: Address::class)]
    #[Groups(['city'])]
    private $addresses;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCp(): ?int
    {
        return $this->cp;
    }

    public function setCp(int $cp): self
    {
        $this->cp = $cp;

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
            $address->setCity($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getCity() === $this) {
                $address->setCity(null);
            }
        }

        return $this;
    }
}
