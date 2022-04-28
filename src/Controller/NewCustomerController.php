<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewCustomerController extends AbstractController
{
    //On crèe des repository pour pouvoir faire des requètes
    private UserRepository $userRepository;

    //On met le repoitory dans le constructor
    public function __construct(UserRepository $userRepository)
    {

        $this->userRepository = $userRepository;
    }
    public function __invoke(Request $request): Response
    {
        $minDateString = $request->query->get('min_date');
        $maxDateString = $request->query->get('max_date');


        //le \ devant dateTime permet de ne pas utiliser le use si on l'enlève il faut rajouter le use
        $minDate = new \DateTime($minDateString);
        $maxDate = new \DateTime($maxDateString);

        dump($minDate);
        dump($maxDate);

        $newCustomerEntities = $this->userRepository->findNewCustomerBetweenDates($minDate, $maxDate);
        count($newCustomerEntities);
        dump($newCustomerEntities);



        return $this->json(['data' => count($newCustomerEntities)]);
    }
}
