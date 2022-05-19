<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\BasketService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BasketController extends AbstractController
{
    private BasketService $basketService;
    private ProductRepository $productRepository;
    private EntityManagerInterface $em;

    /**
     * @param BasketService $basketService
     * @param ProductRepository $productRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(BasketService $basketService, ProductRepository $productRepository, EntityManagerInterface $em)
    {
        $this->basketService = $basketService;
        $this->productRepository = $productRepository;
        $this->em = $em;
    }


    #[Route('/basket', name: 'app_basket')]
    public function index(): Response
    {
        $user = $this->getUser();
        $basket = $this->basketService->getBasket($user);
        dump($basket);

        $countProduct = count($basket->getProducts());

        return $this->render('basket/index.html.twig', [
            'basket' => $basket,
            'countProduct' => $countProduct
        ]);
    }

    #[Route('/basket_add/{id}', name: 'app_basket_add')]
    public function addProduct($id)
    {
        //On récupère l'utilisateur qui est connecté
        $user = $this->getUser();
        if ($user !== null) {
            $product = $this->productRepository->find($id);
            $this->basketService->addProductToBasket($product, $this->getUser());
            return $this->redirectToRoute('app_basket');
        }
    }


    #[Route('/basket_delete/{id}', name: 'app_basket_delete')]
    public function removeProduct($id)
    {
        $user = $this->getUser();
        if($user !== null){
            $product = $this->productRepository->find($id);
            $this->basketService->removeProductToBasket($product, $this->getUser());
            return $this->redirectToRoute('app_basket');
        }
    }


}
