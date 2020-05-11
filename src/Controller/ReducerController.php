<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ReducerController extends AbstractController
{
    /**
     * @Route("/reducer", name="reducer")
     */
    public function index()
    {
        return $this->render('reducer/index.html.twig', [
            'controller_name' => 'ReducerController',
        ]);
    }
}
