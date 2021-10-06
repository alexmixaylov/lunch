<?php

namespace App\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use App\Repository\PersonRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cabinet")
 * @IsGranted("ROLE_USER")
 */
class CabinetController extends AbstractController
{
    /**
     * @Route("/", name="cabinet", methods={"GET"})
     */
    public function index()
    {
        $user = $this->getUser();

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return $this->redirect('/');
        }

        return $this->render('cabinet/index.html.twig', [
            'user_id'            => $user->getId(),
            'name'               => $user->getName(),
            'email'              => $user->getEmail(),
            'roles'              => $user->getRoles(),
            'phone'              => $user->getPhone(),
            'type'               => $user->getUserType(),
            'person_id'          => $user->getPerson() ? $user->getPerson()->getId() : null,
            'related_company_id' => $user->getCompanyOwned() ? $user->getCompanyOwned()->getId() : null
        ]);
    }

    /**
     * @Route("/company/{owner}")
     */
    public function getCompanyByOwner(int $owner, CompanyRepository $repository): JsonResponse
    {
        $company = $repository->findCompanyByOwner($owner);

        return new JsonResponse($company);
    }

    /**
     * @Route("/person", name="cabinet#link_person", methods={"PATCH"})
     */
    public function linkPersonToUser(Request $request, PersonRepository $person_repository): JsonResponse
    {
        $user     = $this->getUser();
        $personId = $request->getContent();
        $person   = $person_repository->find($personId);
        $person->setUser($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($person);
        $em->flush();

        return new JsonResponse($person->getId());
    }

    /**
     * @Route("/company", name="cabinet#link_company", methods={"PATCH"})
     */
    public function linkCompanyOwnerToUser(Request $request, CompanyRepository $company_repository): JsonResponse
    {
        $user      = $this->getUser();
        $title     = $request->getContent();
        $isCompany = $company_repository->find($title);

        if ($isCompany) {
            return new JsonResponse(['message' => "$title - уже есть компания с таким названием"]);
        }

        $company = new Company();
        $company->setTitle($title);
        $company->setOwner($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($company);
        $em->flush();

        return new JsonResponse($company->getUuid());
    }
}
