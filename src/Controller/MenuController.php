<?php

namespace App\Controller;

use App\Repository\MenuRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/menus")
 */
class MenuController extends AbstractController
{

    /**
    * @Route("/", name="menus#list", methods={"GET"})
     */
    public function list(MenuRepository $repository)
    {
        $menus = $repository->findAll();

        return new JsonResponse($menus);
    }

    /**
     * @Route("/{id}", name="menus#read", methods={"GET"})
     */
    public function read(int $id, MenuRepository $repository)
    {
        $menu = $repository->find($id);

        if ( ! $menu) {
            throw $this->createNotFoundException("Menu with ID:{$id} not Found");
        }
        $dishes = $menu->getDishes();
        foreach ($dishes as $dish) {
            print_r($dish->getTitle());
            print_r($dish->getPrice());
            print_r($dish->getWeight());
        }
        return new JsonResponse($dishes);
    }

    /**
     * @Route("/", name="menus#create", methods={"POST"})
     */
    public function create(Request $request)
    {
        return new JsonResponse();
    }

    /**
     * @Route("/{id}", name="menus#update", methods={"PATCH, PUT"})
     */
    public function update(int $id, MenuRepository $repository)
    {
        $menu = $repository->find($id);

        if ( ! $menu) {
            throw $this->createNotFoundException("Menu with ID:{$id} not Found");
        }

        return new JsonResponse();
    }

    /**
     * @Route("/{id}", name="menus#delete", methods={"DELETE"})
     */
    public function delete(int $id)
    {
        return new JsonResponse();
    }


}
