<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\SoapRepository;
use App\Entity\Soap;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\SoapType;



final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/soaps/{id}/delete', name: 'app_admin_soap_delete', methods: ['POST'])]
public function delete(Soap $soap, EntityManagerInterface $em): Response
{
    $em->remove($soap);
    $em->flush();

    return $this->redirectToRoute('app_admin_soaps');
}

#[Route('/admin/soaps/{id}', name: 'app_admin_soap_show')]
public function show(Soap $soap): Response
{
    return $this->render('admin/soap_show.html.twig', [
        'soap' => $soap,
    ]);
}


#[Route('/admin/soaps', name: 'app_admin_soaps')]
public function soaps(SoapRepository $soapRepository, Request $request, EntityManagerInterface $em): Response
{
    $soaps = $soapRepository->findAll();

    $soap = new Soap();
    $form = $this->createForm(SoapType::class, $soap);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($soap);
        $em->flush();

        return $this->redirectToRoute('app_admin_soaps');
    }

    return $this->render('admin/soaps.html.twig', [
        'soaps' => $soaps,
        'form' => $form->createView(),
    ]);
}
}
