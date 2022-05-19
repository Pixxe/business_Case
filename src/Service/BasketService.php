<?php

namespace App\Service;

use App\Entity\Command;
use App\Entity\User;
use App\Repository\CommandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class BasketService
{
    private CommandRepository $commandRepository;
    private EntityManagerInterface $em;

    /**
 * @param CommandRepository $commandRepository
 * @param EntityManagerInterface $em
 */public function __construct(CommandRepository $commandRepository, EntityManagerInterface $em)
{
    $this->commandRepository = $commandRepository;
    $this->em = $em;
}




    public function getBasket(User $user)
    {
        $basketEntity = $this->commandRepository->getBasketByUser($user);

        if($basketEntity === null){
            $basketEntity = new Command();
            $basketEntity->setTotalPrice(0);
            $basketEntity->setNumCommand(uniqid('', true));
            $basketEntity->setCreatedAt(new \DateTime());
            $basketEntity->setStatus(100);
            $basketEntity->setUser($user);
            $this->em->persist($basketEntity);
            $this->em->flush();

        }
        return $basketEntity;

    }

    //Ajouter un produit au panier
    #[Route('/basket_add', name: 'app_basket_add')]
    public function addProductToBasket($productEntity,User $user)
    {

            $basketEntity = $this->getBasket($user);
            $basketEntity->addProduct($productEntity);
            $this->em->persist($basketEntity);
            $this->em->flush();

    }

    #[Route('/basket_delete/{id}', name: 'app_basket_delete')]
    public function removeProductToBasket($productEntity, User $user)
    {
        $basketEntity = $this->getBasket($user);
        $basketEntity->removeProduct($productEntity);
        $this->em->persist($basketEntity);
        $this->em->flush();
    }



}