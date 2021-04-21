<?php

namespace App\Controller;

use App\Repository\StatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\GenerateDates;

/**
 * @Route("/stats")
 */
class StatsController extends AbstractController
{

    /**
     * @Route("/dishes/{date}", name="stats#dishes_by_date" , methods={"GET"})
     */
    public function countDishesByDate($date, StatsRepository $repository)
    {
        $dishes = $repository->countDishesByDate($date);

        return new JsonResponse($dishes);
    }

    /**
     * @Route("/dishes/week/{date}", name="stats#dishes_week" , methods={"GET"})
     */
    public function countDishesByWeek($date, StatsRepository $repository, GenerateDates $generate_dates)
    {
        // отступы

        $dates = $generate_dates->allDatesForWeek($date);


        $dishesWeek = array_map(function ($date) use ($repository) {
            return [
                'dishes' => $repository->countDishesByDate($date),
                'date'   => $date
            ];
        }, $dates);

        return new JsonResponse($dishesWeek);
    }
}
