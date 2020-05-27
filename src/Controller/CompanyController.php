<?php

namespace App\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use App\Repository\OrderRepository;
use App\Repository\PersonRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/companies")
 */
class CompanyController extends AbstractController
{
    /**
     * @Route("/uuid/{uuid}", name="companies#by_uuid", methods={"GET"})
     */
    public function getCompanyByUUID(string $uuid, CompanyRepository $repository, PersonRepository $person_repository)
    {
        $company = $repository->findCompanyByUUID($uuid);

        if ($company) {
            $persons = $person_repository->findByCompanyId($company['company_id']);

            return new JsonResponse([
                'company' => $company,
                'persons' => $persons
            ]);
        }

        return new JsonResponse(null, 404);
    }


    /**
     * @Route("/", name="companies#list", methods={"GET"})
     */
    public function list(CompanyRepository $repository)
    {
        $companies = $repository->findAllCompanies();

        return new JsonResponse($companies);
    }

    /**
     * @Route("/{id}", name="companies#read", methods={"GET"})
     */
    public function read(int $id, CompanyRepository $repository)
    {
        $company = $repository->findCompanyById($id);

        return new JsonResponse($company);
    }

    /**
     * @Route("/", name="companies#create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $post = json_decode($request->getContent(), true);

        $owner   = null;
        $company = new Company();
        $company->setTitle($post['title']);
        if ($owner) {
            $company->setOwner($owner);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($company);
        $em->flush();

        return new JsonResponse([
            'company_id' => $company->getId(),
            'title'=> $company->getTitle()
        ]);
    }

    /**
     * @Route("/{id}", name="companies#delete", methods={"DELETE"})
     */
    public function delete(int $id, CompanyRepository $company_repository, OrderRepository $order_repository)
    {
        $company = $company_repository->find($id);

        if ( ! $company) {
            throw $this->createNotFoundException('Нет такой компании');
        }

        // if Сщ has Orders deny delete
//        $orders = $order_repository->findOrdersByPerson($id);
//        if ($orders) {
//            return new JsonResponse(null,403);
//        }


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($company);
        $entityManager->flush();

        return new JsonResponse('Deleted');
    }

}
