<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{


    public function __construct(private ProductRepository $productRepository)
    {
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

        return $this->render('home/index.html.twig', [
            'bestSellersProductArray' => $products

        ]);

    }



}
