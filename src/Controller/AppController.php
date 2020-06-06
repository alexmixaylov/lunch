<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function index()
    {
        $user = $this->getUser();

        if ( ! in_array("ROLE_ADMIN", $user->getRoles())) {
            return $this->redirect('/cabinet');
        }

        //TODO похоже что это нужно отсюда удалить. Здесь будет только суперюзер, ему можно оставить только роли, кажется
        return $this->render('admin/index.html.twig', [
            'user_id' => $user->getId(),
            'name'    => $user->getName(),
            'email'   => $user->getEmail(),
            'roles'   => $user->getRoles(),
            'phone'   => $user->getPhone(),
            'type'    => $user->getType(),
        ]);
    }
}
