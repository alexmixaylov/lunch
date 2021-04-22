<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Person;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\CompanyRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(
        Request $request,
        UserRepository $user_repository,
        UserPasswordEncoderInterface $passwordEncoder,
        CompanyRepository $company_repository
    ): Response {
        $user          = new User();
        $form          = $this->createForm(RegistrationFormType::class, $user);
        $entityManager = $this->getDoctrine()->getManager();

        // нужно проверить чтобы был суперпользователь, если еще никто не создан, тогда первый пользователь становиться суперюзером
        $isSuperUser = empty($user_repository->findAll());

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            if ($isSuperUser) {
                $user           = self::assignSuperUserRoleIsEmptyDB($user);
                $privateCompany = self::createPrivateCompany();
                $entityManager->persist($privateCompany);
            }

            $entityManager->persist($user);

            if ($form->get('type')->getData() === 'private') {
                $privatePerson = self::createPersonForPrivate($user, $company_repository);
                $entityManager->persist($privatePerson);
            }

            $entityManager->flush();

            // do anything else you need here, like send an email
            return $this->redirectToRoute('app');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * If database is empty we must setup beginning params like SuperUser
     *
     * @param User $user
     */ // название мне не нравится
    private function assignSuperUserRoleIsEmptyDB(User $user): User
    {
        $user->setRoles(['ROLE_ADMIN']);
        $user->setUserType('superuser');

        return $user;
    }


    /**
     * If database is empty we must setup PrivateCompany with UUID == 'private'
     * @return Company
     */
    private function createPrivateCompany(): Company
    {
        $company = new Company();
        $company->setTitle('Частное лицо');
        $company->setUuid('private');

        return $company;
    }

    /**
     * @param User $user
     *
     * @param CompanyRepository $repository
     *
     * @return Person
     */
    private function createPersonForPrivate(User $user, CompanyRepository $repository): Person
    {
        $privateCompany = $repository->findCompanyByUUIDLazyObj('private');
        $person = new Person();
        $person->setName($user->getName());
        $person->setUser($user);
        $person->setCompany($privateCompany);

        return $person;
    }
}
