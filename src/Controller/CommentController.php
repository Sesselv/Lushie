<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CommentController extends AbstractController
{
    // #[Route('/articles/{id}', name: 'article_show')]
    // public function show(
    //     Article $article,
    //     Request $request,
    //     EntityManagerInterface $em,
    //     CommentRepository $commentRepository
    // ): Response {
    //     // Créer un nouveau commentaire
    //     $comment = new Comment();

    //     // Créer le formulaire
    //     $form = $this->createForm(CommentType::class, $comment);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $comment->setUser($this->getUser());
    //         $comment->setArticle($article);
    //         $em->persist($comment);
    //         $em->flush();

    //         return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
    //     }

    //     // Récupérer les commentaires liés à cet article
    //     $comments = $commentRepository->findBy(
    //         ['article' => $article],
    //         ['createdAt' => 'DESC']
    //     );

    //     return $this->render('articles/show.html.twig', [
    //         'article' => $article,
    //         'comments' => $comments,
    //         'form' => $form->createView(), // <-- PAS OUBLIER
    //     ]);
    // }
}
