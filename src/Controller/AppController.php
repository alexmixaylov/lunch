<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app", requirements={"wildcard": ".*"})
     * @IsGranted("ROLE_USER")
     */
    public function index()
    {
        return $this->render('app.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }
}
