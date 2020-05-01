<?php

namespace App\Controller;

use App\Repository\LunchRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/lunches")
 */
class LunchController extends AbstractController
{

    /**
    * @Route("/", name="lunches#list", methods={"GET"})
     */
    public function list(LunchRepository $repository)
    {
        $lunches = $repository->findAll();

        return new JsonResponse($lunches);
    }

    /**
     * @Route("/{id}", name="lunches#read", methods={"GET"})
     */
    public function read(int $id, LunchRepository $repository)
    {
        $lunch = $repository->find($id);

        if ( ! $lunch) {
            throw $this->createNotFoundException("Lunch with ID:{$id} not Found");
        }
        $dishes = $lunch->getDishes();
        foreach ($dishes as $dish) {
            print_r($dish->getTitle());
            print_r($dish->getPrice());
            print_r($dish->getWeight());
        }
        return new JsonResponse($dishes);
    }

    /**
     * @Route("/", name="lunches#create", methods={"POST"})
     */
    public function create(Request $request)
    {
        return new JsonResponse();
    }

    /**
     * @Route("/{id}", name="lunches#update", methods={"PATCH, PUT"})
     */
    public function update(int $id, LunchRepository $repository)
    {
        $lunch = $repository->find($id);

        if ( ! $lunch) {
            throw $this->createNotFoundException("Lunch with ID:{$id} not Found");
        }

        return new JsonResponse();
    }

    /**
     * @Route("/{id}", name="lunches#delete", methods={"DELETE"})
     */
    public function delete(int $id)
    {
        return new JsonResponse();
    }


}
