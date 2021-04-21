<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    // ты не дробишь код на модули, у тебя куча контроллеров и все вперемешку
    // нужно бить код на модули иначе он разрастается и его становится невозможно поддерживать


    // я бы в ямле роуты писал н дело твое
    /**
     * @Route("/", name="app", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function index()
    {
        $user = $this->getUser();

        // где то кавычки " где то '. почитай разницу, юзай " только когда это нужно
        if ( ! in_array("ROLE_ADMIN", $user->getRoles())) {
            return $this->redirect('/cabinet');
        }

        // где то комменты на русском где то н англ. должно быть везде на англ
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
