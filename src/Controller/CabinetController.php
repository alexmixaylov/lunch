<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CabinetController extends AbstractController
{
    /**
     * @Route("/cabinet", name="cabinet")
     */
    public function index()
    {

        return $this->render('cabinet/index.html.twig', [
            'data' => $this->getUser(),
        ]);
    }
}
