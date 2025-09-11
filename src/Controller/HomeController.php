<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\SoapRepository; 


final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(SoapRepository $soapRepository): Response
    {
            $soaps = $soapRepository->findBy([], ['id' => 'DESC'], 3);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'soaps' => $soaps,
        ]);
    }


}
