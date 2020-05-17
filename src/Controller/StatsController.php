<?php

namespace App\Controller;

use App\Repository\StatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\DishesNormalizer;

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
    public function countDishesByWeek($date, StatsRepository $repository)
    {
        define('HOW_MANY_DAYS_ADD', 4);
        // 4 days must be added Then we can get all days per week
        $start = date('Y-m-d', strtotime("monday this week", strtotime($date)));
        $end   = date('Y-m-d', strtotime("+" . HOW_MANY_DAYS_ADD . "days", strtotime($start)));

        // generate dates which should be in base. It is a Dictonary
        $generatePeriod = function ($date, $acc) use (&$generatePeriod, $end) {
            if ($date == $end) {
                $acc[] = $end;

                return $acc;
            }
            $newDate = date('Y-m-d', strtotime("+1days", strtotime($date)));
            $acc[]   = $date;

            return $generatePeriod($newDate, $acc);
        };
        $allWeekDates   = $generatePeriod($start, []);


        $dishesWeek = array_map(function ($date) use ($repository) {
            return [
                'dishes' => $repository->countDishesByDate($date),
                'date'   => $date
            ];
        }, $allWeekDates);

        return new JsonResponse($dishesWeek);
    }
}
