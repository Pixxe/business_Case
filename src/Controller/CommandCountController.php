<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use App\Repository\VisitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandCountController extends AbstractController
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

        $minDate = new \DateTime($minDateString);
        $maxDate = new \DateTime($maxDateString);

        dump($minDate);
        dump($maxDate);

        $commandEntities = $this->commandRepository->findCommandBetwwenDates($minDate, $maxDate);
        count($commandEntities);
        dump($commandEntities);

        return $this->json(['data' => count($commandEntities)]);
    }
}
