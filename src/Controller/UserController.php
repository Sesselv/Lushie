<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Favorite;
use App\Entity\Reaction;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Form\UserProfileType;
use App\Repository\UserRepository;
use App\Repository\MediaRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



final class UserController extends AbstractController
{
    #[Route('/articles', name: 'app_articles')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->render('articles/articles.html.twig', [
            'articles' => $articles,
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////////////////
    #[Route('/user/article', name: 'app_user_create_article')]
    public function articles(Request $request, EntityManagerInterface $em, ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Vérifie que l'utilisateur est connecté
            $user = $this->getUser();
            if (!$user) {
                throw $this->createAccessDeniedException('Vous devez être connecté pour créer un article.');
            }

            $article->setUser($user);
            $article->setCreatedAt(new \DateTimeImmutable());

            // Persiste d'abord l'article pour générer l'ID
            $em->persist($article);
            $em->flush();

            // Gestion des médias
            $images = $form->get('images')->getData();
            if ($images) {
                foreach ($images as $image) {
                    $newFilePath = uniqid() . '.' . $image->guessExtension();
                    $image->move(
                        $this->getParameter('uploads_articles_directory'),
                        $newFilePath
                    );

                    $media = new Media();
                    $media->setFilePath($newFilePath);
                    $media->setUser($user);
                    $media->setCreatedAt(new \DateTimeImmutable());

                    // Ajoute le media à l'article pour la relation bidirectionnelle
                    $article->addMedium($media);

                    $em->persist($media);
                }
                $em->flush();
            }

            return $this->redirectToRoute('app_articles');
        }

        return $this->render('user/create_article.html.twig', [
            'form' => $form->createView(),
            'articles' => $articles,
        ]);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    #[Route('/user', name: 'app_user_dashboard')]
    public function dashboard(Request $request, EntityManagerInterface $em, MediaRepository $mediaRepo): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Gestion de la photo de profil
            if ($photo = $form->get('photo')->getData()) {
                $filename = 'user_' . $user->getUserIdentifier() . '_' . uniqid() . '.' . $photo->guessExtension();
                $photo->move($this->getParameter('uploads_users_directory'), $filename);

                $media = $mediaRepo->findOneBy(['user' => $user]) ?? new Media();
                if (!$media->getId()) {
                    $media->setUser($user);
                    $media->setCreatedAt(new \DateTimeImmutable());
                    $em->persist($media);
                }

                $media->setFilePath($filename);
            }

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_user_dashboard');
        }

        // Photo de profil avec fallback
        $profilePhoto = $mediaRepo->findOneBy(['user' => $user]);
        $profilePhotoPath = $profilePhoto
            ? 'uploads/users/' . $profilePhoto->getFilePath()
            : 'uploads/users/pp.png';

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'profilePhotoPath' => $profilePhotoPath,
            'form' => $form->createView(),
        ]);
    }

    /////////////////////////////////////////
    #[Route('/user/delete-photo', name: 'app_user_delete_photo', methods: ['POST'])]
    public function deletePhoto(EntityManagerInterface $em, MediaRepository $mediaRepo): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté.');
        }

        $media = $mediaRepo->findOneBy(['user' => $user]);
        if ($media) {
            $filePath = $this->getParameter('uploads_users_directory') . '/' . $media->getFilePath();
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $em->remove($media);
            $em->flush();
        }

        $this->addFlash('success', 'Photo de profil supprimée.');
        return $this->redirectToRoute('app_user_dashboard');
    }


    /////////////////////////////////////////
    #[Route('/user/detail/{type}/{userId}', name: 'app_user_details')]
    public function detail(
        string $type,
        int $userId,
        UserRepository $userRepo,
        ArticleRepository $articleRepo,
        CommentRepository $commentRepo,
        FavoriteRepository $favoriteRepo
    ): Response {
        $user = $userRepo->find($userId);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        if ($type === 'articles') {
            $data = $articleRepo->findBy(['user' => $user]);
        } elseif ($type === 'commentaires') {
            $data = $commentRepo->findBy(['user' => $user]);
        } elseif ($type === 'favorite') {
            $data = $favoriteRepo->findBy(['user' => $user]);
        } else {
            throw $this->createNotFoundException('Type invalide.');
        }

        return $this->render('user/details.html.twig', [
            'type' => $type,
            'data' => $data,
            'user' => $user
        ]);
    }

    ///////////////////////////////////////////////////////////////////

    #[Route('/article/{id}', name: 'app_article_show')]
    public function show(
        Article $article,
        Request $request,
        EntityManagerInterface $em,
        CommentRepository $commentRepository
    ): Response {
        // Créer un nouveau commentaire
        $comment = new Comment();

        // Créer le formulaire
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setArticle($article);
            $comment->setCreatedAt(new \DateTimeImmutable());
            $em->persist($comment);
            $em->flush();

            // Si c'est une requête AJAX, retourner JSON
            if ($request->isXmlHttpRequest()) {
                // Récupérer les commentaires mis à jour
                $comments = $commentRepository->findBy(['article' => $article], ['createdAt' => 'DESC']);

                return new JsonResponse([
                    'success' => true,
                    'message' => 'Commentaire ajouté avec succès',
                    'comment' => [
                        'id' => $comment->getId(),
                        'content' => $comment->getContent(),
                        'username' => $comment->getUser()->getUsername(),
                        'createdAt' => $comment->getCreatedAt()->format('d/m/Y'),
                        'userPhoto' => $comment->getUser()->getMedia()->first() ?
                            '/uploads/users/' . $comment->getUser()->getMedia()->first()->getFilePath() :
                            '/images/pp.png'
                    ],
                    'totalComments' => count($comments)
                ]);
            }

            return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
        }

        // Récupérer tous les commentaires pour cet article
        $comments = $commentRepository->findBy(['article' => $article], ['createdAt' => 'DESC']);

        return $this->render('articles/show.html.twig', [
            'article' => $article,
            'comments' => $comments,
            'form' => $form->createView(), // <-- ceci fixe l'erreur Twig
        ]);
    }

    ////////////////////////////////////////////////////////////////////

    #[Route('/article/{id}/delete', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, ?Article $article, EntityManagerInterface $em): Response
    {
        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé.');
        }

        $user = $this->getUser();
        if (!$user instanceof \App\Entity\User) {
            throw $this->createAccessDeniedException('Vous devez être connecté.');
        }

        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $em->remove($article);
            $em->flush();
            $this->addFlash('success', 'Article supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_user_details', [
            'userId' => $user->getId(),
            'type' => 'articles'
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////

    #[Route('/comment/{id}/delete', name: 'app_comment_delete', methods: ['POST'])]
    public function deleteComment(Request $request, ?Comment $comment, EntityManagerInterface $em): Response
    {
        if (!$comment) {
            throw $this->createNotFoundException('Commentaire non trouvé.');
        }

        $user = $this->getUser();
        if (!$user instanceof \App\Entity\User) {
            throw $this->createAccessDeniedException('Vous devez être connecté.');
        }

        if ($comment->getUser() !== $user) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas supprimer ce commentaire.');
        }


        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $em->remove($comment);
            $em->flush();
            $this->addFlash('success', 'Commentaire supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_user_details', [
            'userId' => $user->getId(),
            'type' => 'commentaires'
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////////
    #[Route('/favorite/{id}/remove', name: 'app_favorite_remove', methods: ['POST'])]
    public function removeFavorite(Request $request, ?Favorite $favorite, EntityManagerInterface $em): Response
    {
        if (!$favorite) {
            throw $this->createNotFoundException('Favori non trouvé.');
        }

        $user = $this->getUser();
        if (!$user instanceof \App\Entity\User) {
            throw $this->createAccessDeniedException('Vous devez être connecté.');
        }

        if ($this->isCsrfTokenValid('remove' . $favorite->getId(), $request->request->get('_token'))) {
            $em->remove($favorite);
            $em->flush();
            $this->addFlash('success', 'Favori retiré avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_user_details', [
            'userId' => $user->getId(),
            'type' => 'favorite'
        ]);
    }
    //////////////////////////////////////////////////////////////////


}
