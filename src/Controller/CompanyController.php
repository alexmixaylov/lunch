<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/companies")
 */
class CompanyController extends AbstractController
{
    /**
     * @Route("/slug/{slug}", name="companies#by_slug")
     */
    public function getCompanyBySlug(string $slug, CompanyRepository $repository)
    {
        $company = $repository->findCompanyBySlug($slug);

        return new JsonResponse($company);
    }


    /**
     * @Route("/", name="companies#list")
     */
    public function list(CompanyRepository $repository)
    {
        $companies = $repository->findAllCompanies();

        return new JsonResponse($companies);
    }

    /**
     * @Route("/{id}", name="companies#read")
     */
    public function read(int $id, CompanyRepository $repository)
    {
        $company = $repository->findCompanyById($id);

        return new JsonResponse($company);
    }


}
