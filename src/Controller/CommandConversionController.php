<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandConversionController extends AbstractController
{

    private CommandRepository $commandRepository;


    public function __construct(CommandRepository $commandRepository)
    {

        $this->commandRepository = $commandRepository;
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

        $commandEntities = $this->commandRepository->findCommandBetwwenDates($minDate, $maxDate);
        $basketEntities = $this->commandRepository->findBasketBetweenDates($minDate, $maxDate);

        $numberOfCommand = count($commandEntities);
        $numberOfBasket = count($basketEntities);
        dump($numberOfBasket);
        dump($numberOfCommand);

        $result = ($numberOfBasket * 100) / $numberOfCommand;

        return $this->json($result);
    }
}
