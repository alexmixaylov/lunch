<?php

namespace App\Controller;

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

        if ( ! in_array('ROLE_ADMIN', $user->getRoles())) {
            return $this->redirect('/cabinet');
        }

        return $this->render('admin/index.html.twig', []);
    }
}
