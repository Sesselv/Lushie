<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SoapRepository;
use App\Repository\RatingRepository;
use App\Entity\Soap;
use App\Entity\Rating;

final class SoapsController extends AbstractController
{
    #[Route('/soaps', name: 'app_soaps')]
    public function index(SoapRepository $soapRepository): Response
    {
        $soaps = $soapRepository->findAll();

        return $this->render('soaps/soaps.html.twig', [
            'soaps' => $soaps,
            'controller_name' => 'SoapsController',
        ]);
    }

    #[Route('/soaps/{id}', name: 'app_soap_show')]
    public function show(Soap $soap, RatingRepository $ratingRepo): Response
    {
        $user = $this->getUser();
        $rating = null;

        if ($user) {
            $rating = $ratingRepo->findOneBy([
                'soap' => $soap,
                'user' => $user
            ]);
        }

        return $this->render('soaps/show.html.twig', [
            'soap' => $soap,
            'rating' => $rating,
        ]);
    }

    #[Route('/rate', name: 'soap_rate', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function rate(Request $request, EntityManagerInterface $em, RatingRepository $ratingRepo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $soapId = $data['soapId'] ?? null;
        $note = $data['note'] ?? null;

        if (!$soapId || $note === null) {
            return new JsonResponse(['error' => 'Invalid data'], 400);
        }

        $soap = $em->getRepository(Soap::class)->find($soapId);
        if (!$soap) {
            return new JsonResponse(['error' => 'Soap not found'], 404);
        }

        $user = $this->getUser();
        $rating = $ratingRepo->findOneBy(['user' => $user, 'soap' => $soap]);

        if ($note === 0 && $rating) {
            $em->remove($rating);
        } else {
            if (!$rating) {
                $rating = new Rating();
                $rating->setUser($user);
                $rating->setSoap($soap);
                $rating->setCreatedAt(new \DateTimeImmutable());
            }
            $rating->setNote($note);
            $em->persist($rating);
        }

        $em->flush();

        return new JsonResponse(['success' => true]);
    }
}
