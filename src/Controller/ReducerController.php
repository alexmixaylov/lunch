<?php

namespace App\Controller;

use App\Repository\ReducerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/stats")
 */
class ReducerController extends AbstractController
{

    /**
     * @Route("/dishes/{date}", name="stats#dishes_by_date" , methods={"GET"})
     */
    public function countDishesByDate($date, ReducerRepository $repository)
    {
        $dishes = $repository->countDishesByDate($date);

        return new JsonResponse($dishes);
    }
}
