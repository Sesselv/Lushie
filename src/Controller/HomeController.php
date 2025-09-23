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

    #[Route('/mentions-legales', name: 'mentions_legales')]
    public function mentions(): Response
    {
        return $this->render('home/mentions.html.twig');
    }

    #[Route('/rgpd', name: 'rgpd')]
    public function politique(): Response
    {
        return $this->render('home/rgpd.html.twig');
    }

    #[Route('/cookies', name: 'cookies')]
    public function cookies(): Response
    {
        return $this->render('home/gdc.html.twig');
    }

    #[Route('/shops', name: 'shops')]
    public function shops(): Response
    {
        return $this->render('shops.html.twig');
    }
}
