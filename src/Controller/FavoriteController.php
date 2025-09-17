<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FavoriteRepository;
use App\Entity\Soap;
use App\Entity\Favorite;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


final class FavoriteController extends AbstractController
{
    #[Route('/favorite', name: 'app_favorite')]
    public function index(FavoriteRepository $favoriteRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour voir vos favoris.');
                   return new Response(); 
        }

        $favorites = $favoriteRepository->findBy(['user' => $user]);

        return $this->render('favorite/index.html.twig', [
            'favorites' => $favorites,
        ]);
    }

    #[Route('/favorite/soap/{id}', name: 'toggle_favorite', methods: ['POST'])]
   #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function toggleFavorite(
        Soap $soap,
        FavoriteRepository $favoriteRepository,
        EntityManagerInterface $em
    ): Response {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour ajouter un favori.');
            return $this->redirectToRoute('app_login');
        }

        $favorite = $favoriteRepository->findOneBy([
            'user' => $user,
            'soap' => $soap,
        ]);

if ($favorite) {

    $em->remove($favorite);
    $em->flush();
    $this->addFlash('success', 'Savon retiré de vos favoris.');
} else {

    $favorite = new Favorite();
    $favorite->setUser($user);
    $favorite->setSoap($soap);
    $em->persist($favorite);
    $em->flush();
    $this->addFlash('success', 'Savon ajouté à vos favoris.');
}

    return new Response();

    }
}
