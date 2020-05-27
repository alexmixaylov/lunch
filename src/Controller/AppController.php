<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app", methods={"GET"})
     */
    public function index()
    {
        $user = $this->getUser();
        if ( ! $user) {
            return $this->redirect('/login');
        }

        if ( ! in_array("ROLE_ADMIN", $user->getRoles())) {
            return $this->redirect('/cabinet');
        }

        return $this->render('app.html.twig', [
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
}
