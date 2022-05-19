<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductPicture;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminProductController extends AbstractController
{


    public function __construct(private ProductRepository $productRepository, private EntityManagerInterface $em)
    {
    }

    #[Route('/admin/product', name: 'app_admin_product')]
    public function index(): Response
    {
        return $this->render('admin_product/index.html.twig', [
            'controller_name' => 'AdminProductController',
        ]);
    }

    #[Route('/admin/add_product', name: 'app_admin_add_product')]
    public function addProduct(Request $request, SluggerInterface $slugger):Response
    {
        $product = new Product();
        //Pas besoin de faire un getData si l'on est sur une entité
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $productFile = $form->get('image')->getData();
            if($productFile){
                $originalFileName = pathinfo($productFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFileName);
                $newFilename = $safeFilename.'-'.uniqid('', true).'.'.$productFile->guessExtension();

                try {
                    $productFile->move(
                        //Variable du chemin que l'on a définie dans services.yaml
                        $this->getParameter('upload_image'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $productPictureEntity = new ProductPicture();
                $productPictureEntity->setPath('uploads/images/'. $newFilename);
                $productPictureEntity->setLibele($originalFileName);

                $product->addProductPicture($productPictureEntity);
            }
            $this->em->persist($product);
            $this->em->flush();
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin_product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
