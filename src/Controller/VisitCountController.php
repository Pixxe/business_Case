<?php

namespace App\Controller;

use App\Repository\VisitRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VisitCountController extends AbstractController
{
    private VisitRepository $visitRepository;
    public function __construct(VisitRepository $visitRepository)
    {
        $this->visitRepository = $visitRepository;
    }

    public function __invoke(Request $request): Response
    {
        $minDateString = $request->query->get('date_min');
        $maxDateString = $request->query->get('date_max');

        $minDate = new \DateTime($minDateString);
        $maxDate = new \DateTime($maxDateString);

        dump($minDate);
        dump($maxDate);

        $visitEntities = $this->visitRepository->getVisitBetweenDates($minDate, $maxDate);
        count($visitEntities);
        dump($visitEntities);

        return $this->json(count($visitEntities));
    }

    
}
