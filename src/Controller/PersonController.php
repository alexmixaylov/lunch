<?php

namespace App\Controller;

use App\Entity\Person;
use App\Repository\CompanyRepository;
use App\Repository\OrderRepository;
use App\Repository\PersonRepository;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/company/{id}", name="persons#by_company", methods={"GET"})
     */
    public function getPersonsByCompany(int $id, PersonRepository $repository): JsonResponse
    {
        $persons = $repository->findByCompanyId($id);

        return new JsonResponse($persons);
    }

    /**
     * @Route("/user/{id}", name="persons#by_user", methods={"GET"})
     */
    public function getPersonsByUserId(int $id, PersonRepository $repository): JsonResponse
    {
        $person = $repository->findByUserId($id);

        return new JsonResponse($person);
    }

    /**
     * @Route("/{id}", name="persons#read", methods={"GET"})
     */
    public function read(int $id, PersonRepository $repository): JsonResponse
    {
        $person = $repository->findById($id);

        return new JsonResponse($person);
    }

    /**
     * @Route("/create", name="persons#create", methods={"POST"})
     */
    public function create(Request $request, CompanyRepository $company_repository): JsonResponse
    {
        $post = json_decode($request->getContent(), true); // ты уверен что тут флаг нужен?

        $company = $company_repository->find($post['company_id']);

        if ( ! $company) {
            throw new Exception('Нет компании с таким ID');
        }

        $person = new Person();
        $person->setCompany($company);
        $person->setName($post['name']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($person);
        $em->flush();
// где то ты выносить респонс в отдельный массив $response, тут сразу пробрасываешь. почему?
        return new JsonResponse([
            'person_id' => $person->getId(),
            'name'      => $person->getName()
        ]);
    }

    /**
     * @Route("/{id}", name="persons#update", methods={"PATCH"})
     */
    public function update(int $id, Request $request, PersonRepository $repository): JsonResponse
    {
        $person = $repository->find($id);

        if ( ! $person) {
            throw $this->createNotFoundException("Нет персоны c таким ID:${$id}");
        }
        $post = json_decode($request->getContent(), true);

        $person->setName($post['name']);
        $em = $this->getDoctrine()->getManager();
        $em->persist($person);
        $em->flush();

        return new JsonResponse('Updated');
    }

    /**
     * @Route("/{id}", name="persons#delete", methods={"DELETE"})
     */
    public function delete(int $id, PersonRepository $repository, OrderRepository $order_repository): JsonResponse
    {
        $person = $repository->find($id);

        if ( ! $person) {
            throw $this->createNotFoundException('Нет такого человека');
        }

        // if Person has Orders deny delete
        $orders = $order_repository->findOrdersByPerson($id);
        if ($orders) {
            return new JsonResponse(null,403);
        }


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($person);
        $entityManager->flush();

        return new JsonResponse('Deleted');
    }

}
