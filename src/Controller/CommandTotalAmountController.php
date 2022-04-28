<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandTotalAmountController extends AbstractController
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

        $commandAmountEntities = $this->commandRepository->findCommandAmountBetweenDates($minDate, $maxDate);
        count($commandAmountEntities);
        dump($commandAmountEntities);

        $command = 0;

        foreach ($commandAmountEntities as $objetCommand) {
            $command += $objetCommand->getTotalPrice();
        }

        dump($command);

        return $this->json($command);
    }
}
