<?php


// src/Controller/SoapComparatorController.php
namespace App\Controller;

use App\Repository\SoapRepository; // Assure-toi que ton repository existe
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SoapComparatorController extends AbstractController
{
    #[Route('/comparator', name: 'soap_comparator')]
    public function comparator(SoapRepository $soapRepository): Response
    {
        $soaps = $soapRepository->findAll();

        return $this->render('comparator.html.twig', [
            'soaps' => $soaps,
        ]);
    }
}
