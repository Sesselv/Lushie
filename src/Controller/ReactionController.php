<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Reaction;

final class ReactionController extends AbstractController
{
    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // cette méthode peut rester vide, elle sera interceptée par Symfony
        throw new \LogicException('Cette méthode est interceptée par le firewall de Symfony.');
    }
    //////////////////////////////////////////////////////////////////////

    #[Route('/article/{id}/reaction', name: 'app_article_reaction', methods: ['POST'])]
    public function reaction(Article $article, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        // Vérifier si c'est un user normal
        if (!$user || in_array('ROLE_ADMIN', $user->getRoles())) {
            return new JsonResponse(['success' => false, 'message' => 'Non autorisé']);
        }

        // Vérifie si l'utilisateur a déjà réagi
        $existingReaction = $em->getRepository(Reaction::class)->findOneBy([
            'article' => $article,
            'user' => $user
        ]);

        $hasReaction = false;
        if ($existingReaction) {
            $em->remove($existingReaction);
        } else {
            $reaction = new Reaction();
            $reaction->setUser($user);
            $reaction->setArticle($article);
            $em->persist($reaction);
            $hasReaction = true;
        }

        $em->flush();

        // Redirection normale vers la page de l'article
        return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
    }
}
