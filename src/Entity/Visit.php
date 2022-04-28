<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\VisitCountController;
use App\Repository\VisitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: VisitRepository::class)]
#[ApiResource(
    collectionOperations: [
        'GET', 'POST',
        'get_total_visit_from_dates' => [
            'method' => 'GET',
            'path' => '/visit/get_total_visit',
            'controller' => VisitCountController::class
        ]

    ],
    itemOperations: ['GET'],
    normalizationContext: ['groups' => ['productPicture']]


)]
class Visit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    #[
        Assert\Type(
            type: 'datetime',
            message: 'Votre date doit être de type {{ type }}.',
        ),
        Assert\NotNull(
            message: 'La valeur ne doit pas être NULL',
        ),
        Assert\NotBlank(
            message: 'Votre champ est vide.',
            normalizer: 'trim',
        ),
    ]
    private $visitedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVisitedAt(): ?\DateTimeInterface
    {
        return $this->visitedAt;
    }

    public function setVisitedAt(\DateTimeInterface $visitedAt): self
    {
        $this->visitedAt = $visitedAt;

        return $this;
    }
}
