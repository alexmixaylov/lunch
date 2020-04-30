<?php

namespace App\Controller;

use App\Entity\Dish;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dishes")
 */
class DishController extends AbstractController
{
    /**
     * @Route("/", name="all_dishes", methods={"GET"})
     */
    public function index()
    {
        return new JsonResponse();
    }

    /**
     * @Route("/{id}", name="single_dish", methods={"GET"}, options={})
     */
    public function dish(int $id)
    {
        return new JsonResponse($id);
    }

    /**
     * @Route("/", name="add_dish", methods={"POST"})
     */
    public function add(Request $request)
    {
        $dish = new Dish();
        $dish->setPrice();
        $dish->setWeight();
        $dish->setType();
        $dish->setTitle();


        return new JsonResponse();
    }

    /**
     * @Route("/{id}", name="update_dish", methods={"PATCH, PUT"})
     */
    public function update(int $id)
    {
        return new JsonResponse();
    }

    /**
     * @Route("/{id}", name="del_dish", methods={"DELETE"})
     */
    public function delete(int $id)
    {
        return new JsonResponse();
    }

}
