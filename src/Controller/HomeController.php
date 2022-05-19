<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use App\Repository\ProductRepository;
use App\Service\BasketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    private BasketService $basketService;
    private ProductRepository $productRepository;

    /**
     * @param BasketService $basketService
     * @param ProductRepository $productRepository
     */
    public function __construct(BasketService $basketService, ProductRepository $productRepository)
    {
        $this->basketService = $basketService;
        $this->productRepository = $productRepository;
    }


    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        //Afficher les produits les plus vendus
        $bestSellersProduct = $this->productRepository->bestSellersProduct();

        $products =[];
        foreach ($bestSellersProduct as $product){
            array_push($products ,$product[0]);
        }
        dump($bestSellersProduct);

        $user = $this->getUser();
        $basket = $this->basketService->getBasket($user);
        dump($basket);



        return $this->render('home/index.html.twig', [
            'bestSellersProductArray' => $products,


        ]);


    }



}
