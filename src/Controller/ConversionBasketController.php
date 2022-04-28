<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use App\Repository\VisitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConversionBasketController extends AbstractController
{
    private CommandRepository $commandRepository;
    private VisitRepository $visitRepository;

    //On met le repoitory dans le constructor
    public function __construct(CommandRepository $commandRepository, VisitRepository $visitRepository)
    {

        $this->commandRepository = $commandRepository;
        $this->visitRepository = $visitRepository;
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

        $basketEntities = $this->commandRepository->findBasketBetweenDates($minDate, $maxDate);
        count($basketEntities);
        dump($basketEntities);

        $visitEntities = $this->visitRepository->getVisitBetweenDates($minDate, $maxDate);
        count($visitEntities);
        dump($visitEntities);

        $erreur = 0;
        if (count($visitEntities) !== 0) {
            $basketConversion = ((count($basketEntities) * 100) / count($visitEntities));
            return $this->json($basketConversion);
        } else {
            return $this->json($erreur);
        }
    }
}
