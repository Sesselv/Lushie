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

    //////////////////////////////////////////////////////

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


        $sum = 0;
        foreach ($soap->getRatings() as $rating) {
            $sum += $rating->getNote();
        }

        $average = 0;
        if ($sum > 0) {
            $average = $sum / count($soap->getRatings());
        }




        $benefits = [];
        if ($soap->getBenefits()) {

            $benefits = array_map('trim', explode(',', $soap->getBenefits()));
        }

        $precautions = [];
        if ($soap->getPrecautions()) {
            $precautions = array_map('trim', explode(',', $soap->getPrecautions()));
        }


        return $this->render('soaps/show.html.twig', [
            'soap' => $soap,
            'rating' => $rating,
            'average' => $average
        ]);
    }
    ///////////////////////////////////////////////////////////////////////////////////

    #[Route('/rate', name: 'soap_rate', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function rate(Request $request, EntityManagerInterface $em, RatingRepository $ratingRepo): JsonResponse
    {

        //Je récupére ce le js qui est dans le  body du json
        $data = json_decode($request->getContent(), true);

        //recup la valeur soap et note de la data
        $soapId = $data['soapId'] ?? null;
        $note = $data['note'] ?? null;

        //verif que les deux sont bien définis sinon on retourne une erreur
        if (!$soapId || $note === null) {
            return new JsonResponse(['error' => 'Invalid data'], 400);
        }

        // Cherche en base de données l'entité Soap correspondant à l’identifiant $soapId avc l’EntityManager ($em) si r trouvé on retourne une erreur
        $soap = $em->getRepository(Soap::class)->find($soapId);
        if (!$soap) {
            return new JsonResponse(['error' => 'Soap not found'], 404);
        }

        //on recup utilasateur connecté et on regarde si il y'a un note qui existe pour cet utilisateur<
        $user = $this->getUser();
        $rating = $ratingRepo->findOneBy(['user' => $user, 'soap' => $soap]);

        //Si la nouvelle note vaut 0 et qu’il existe déjà une note enregistrée, alors on supprime la note (l’utilisateur retire sa note).
        if ($note === 0 && $rating) {
            $em->remove($rating);

            //si la note est pas 0 ou qu'il y avait pas encore de note) : Si aucune note existante :
            //On crée un nouvel objet Rating.
            //On associe l’utilisateur (setUser) et le savon (setSoap).
            //On définit une date de création (setCreatedAt).
            //Ensuite que ce soit une création ou une mise à jour on applique la note (setNote) et on demande à Doctrine de la sauvegarder (persist).
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
        //Exécute réellement toutes les modifications en base de données (insert/update/delete).
        $em->flush();

        // Calculer la nouvelle moyenne et le nombre total de votes
        $ratings = $soap->getRatings();
        $totalVotes = count($ratings);
        $sum = 0;
        foreach ($ratings as $ratingItem) {
            $sum += $ratingItem->getNote();
        }
        $averageRating = $totalVotes > 0 ? $sum / $totalVotes : 0;

        //Envoie la rep avec les nouvelles données
        return new JsonResponse([
            'success' => true,
            'averageRating' => round($averageRating, 1),
            'totalVotes' => $totalVotes
        ]);
    }
}
