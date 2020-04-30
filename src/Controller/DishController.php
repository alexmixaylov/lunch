<?php

namespace App\Controller;

use App\Entity\Dish;
use App\Repository\DishRepository;
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
     * @Route("/", name="dishes#list", methods={"GET"})
     */
    public function list(DishRepository $repository)
    {
        $dishes = $repository->findAll();

        return new JsonResponse($dishes);
    }

    /**
     * @Route("/{id}", name="dishes#read", methods={"GET"}, options={})
     */
    public function read(int $id, DishRepository $repository)
    {
        $dish = $repository->find($id);

        if ( ! $dish) {
            throw $this->createNotFoundException("Dish with ID:{$id} not Found");
        }

        return new JsonResponse($dish);
    }

    /**
     * @Route("/", name="dishes#create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $dish = new Dish();
        $dish->setPrice();
        $dish->setWeight();
        $dish->setType();
        $dish->setTitle();

        return new JsonResponse();
    }

    /**
     * @Route("/{id}", name="dishes#update", methods={"PATCH, PUT"})
     */
    public function update(int $id, DishRepository $repository)
    {
        $dish = $repository->find($id);

        if ( ! $dish) {
            throw $this->createNotFoundException("Dish with ID:{$id} not Found");
        }

        return new JsonResponse();
    }

    /**
     * @Route("/{id}", name="dishes#delete", methods={"DELETE"})
     */
    public function delete(int $id)
    {
        return new JsonResponse();
    }

}
