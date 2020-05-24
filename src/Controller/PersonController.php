<?php

namespace App\Controller;

use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PersonController
 * @package App\Controller
 * @Route("/persons")
 */
class PersonController extends AbstractController
{
    /**
     * @Route("/company/{id}", name="persons#by_company")
     */
    public function getPersonsByCompany(int $id, PersonRepository $repository)
    {
        $persons = $repository->findByCompanyId($id);

        return new JsonResponse($persons);
    }

    /**
     * @Route("/{id}", name="persons#read", methods={"GET"})
     */
    public function read(int $id, PersonRepository $repository)
    {
        $person = $repository->findById($id);

        return new JsonResponse($person);
    }
}
