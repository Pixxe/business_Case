<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecurrenceCommandController extends AbstractController
{
    private CommandRepository $commandRepository;

    //On met le repoitory dans le constructor
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

        $RecurrenceCommandNewCustomer = $this->commandRepository->findRecurrenceBasketNewCustomerBetweenDates($minDate, $maxDate);
        $RecurrenceCommandOldCustomer = $this->commandRepository->findRecurrenceBasketOldCustomerBetweenDates($minDate, $maxDate);

        dump($RecurrenceCommandOldCustomer);
        dump($RecurrenceCommandNewCustomer);

        $recurrence = 0;

        if (count($RecurrenceCommandNewCustomer) !== 0) {
            $result = ((count($RecurrenceCommandNewCustomer) - count($RecurrenceCommandOldCustomer)) / count($RecurrenceCommandNewCustomer)) * 100;
            return $this->json($result);
        } else {
            return $this->json($recurrence);
        }
    }
}
