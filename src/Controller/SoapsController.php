<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\SoapRepository;
use App\Entity\Soap;


final class SoapsController extends AbstractController
{
    #[Route('/soaps', name: 'app_soaps')]
    public function index(SoapRepository $soapRepository): Response
    {
        $soaps = $soapRepository->findAll();

        return $this->render('soaps/soaps.html.twig', [
            'soaps' => $soaps,
             'controller_name' => 'SoapController',
        ]);
    }

    #[Route('/soaps/{id}', name: 'app_soap_show')]
    public function showSoap(Soap $soap): Response
   {
    return $this->render('soaps/show.html.twig', [
        'soap' => $soap,
    ]);
}
}
