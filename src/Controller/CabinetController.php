<?php

namespace App\Controller;

use App\Repository\PersonRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CabinetController extends AbstractController
{
    /**
     * @Route("/cabinet", name="cabinet", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function index()
    {
        $user = $this->getUser();

        return $this->render('cabinet/index.html.twig', [
            'user_id' => $user->getId(),
            'name'    => $user->getName(),
            'email'   => $user->getEmail(),
            'roles'   => $user->getRoles(),
            'phone'   => $user->getPhone(),
            'type'    => $user->getType(),
            'person'  => $user->getPerson() ? $user->getPerson()->getName() : null,
            'company' => $user->getPerson() ? $user->getPerson()->getCompany()->getTitle() : null
        ]);
    }

    /**
     * @Route("/cabinet/link", name="cabinet#link", methods={"PATCH"})
     * @IsGranted("ROLE_USER")
     */
    public function linkPersonToUser(Request $request, PersonRepository $person_repository)
    {
        $user     = $this->getUser();
        $personId = $request->getContent();
        $person   = $person_repository->find($personId);
        $user->setPerson($person);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new JsonResponse($user->getPerson()->getName());
    }
}
