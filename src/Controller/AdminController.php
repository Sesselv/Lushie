<?php

namespace App\Controller;

use App\Entity\Soap;
use App\Entity\User;
use App\Entity\Media;
use App\Form\SoapType;
use App\Repository\SoapRepository;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;





final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    ////////////////////////////////////////
    #[Route('/admin/soaps/{id}/delete', name: 'app_admin_soap_delete', methods: ['POST'])]
    public function delete(Soap $soap, EntityManagerInterface $em): Response
    {
        $em->remove($soap);
        $em->flush();

        return $this->redirectToRoute('app_admin_soaps');
    }
    ////////////////////////////////////////////////////////

    #[Route('/admin/soaps/{id}/edit', name: 'app_admin_soap_edit')]
    public function edit(Soap $soap, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SoapType::class, $soap);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            if ($images) {
                foreach ($images as $image) {
                    $newFilePath = uniqid() . '.' . $image->guessExtension();
                    $image->move($this->getParameter('uploads_soaps_directory'), $newFilePath);

                    $media = new Media();
                    $media->setFilePath($newFilePath);
                    $media->setSoap($soap);
                    $media->setUser($this->getUser());
                    $media->setCreatedAt(new \DateTimeImmutable("now"));
                    $em->persist($media);
                }
            }

            $em->flush();

            return $this->redirectToRoute('app_admin_soaps');
        }

        return $this->render('admin/soap_edit.html.twig', [
            'soap' => $soap,
            'form' => $form->createView(),
        ]);
    }
    ////////////////////////////////////////////////////////

    // #[Route('/admin/soaps/{id}', name: 'app_admin_soap_show')]
    // public function soapShow(Soap $soap): Response
    // {
    //     return $this->render('admin/soap_show.html.twig', [
    //         'soap' => $soap,
    //     ]);
    // }
    ////////////////////////////////////////////////////////

    #[Route('/admin/soaps/new', name: 'app_admin_soap_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $soap = new Soap();
        $form = $this->createForm(SoapType::class, $soap);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $soap->setCreatedAt(new \DateTimeImmutable("now"));

            $images = $form->get('images')->getData();
            if ($images) {
                foreach ($images as $image) {
                    $newFilePath = uniqid() . '.' . $image->guessExtension();
                    $image->move($this->getParameter('uploads_soaps_directory'), $newFilePath);

                    $media = new Media();
                    $media->setFilePath($newFilePath);
                    $media->setSoap($soap);
                    $media->setUser($this->getUser());
                    $media->setCreatedAt(new \DateTimeImmutable("now"));
                    $em->persist($media);
                }
            }

            $em->persist($soap);
            $em->flush();

            return $this->redirectToRoute('app_admin_soaps');
        }

        return $this->render('admin/soap_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    ////////////////////

    #[Route('/admin/soaps', name: 'app_admin_soaps')]
    public function soaps(SoapRepository $soapRepository, Request $request, EntityManagerInterface $em): Response
    {
        $soaps = $soapRepository->findAll();

        $soap = new Soap();
        $form = $this->createForm(SoapType::class, $soap);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Set creation date
            $soap->setCreatedAt(new \DateTimeImmutable("now"));
            $images = $form->get('images')->getData();
            if ($images) {
                foreach ($images as $image) {
                    $newFilePath = uniqid() . '.' . $image->guessExtension();


                    $image->move($this->getParameter('uploads_soaps_directory'), $newFilePath);

                    $media = new Media();
                    $media->setFilePath($newFilePath);
                    $media->setSoap($soap);
                    $media->setUser($this->getUser());


                    $media->setCreatedAt(new \DateTimeImmutable("now"));
                    $em->persist($media);
                }
            }

            $em->persist($soap);

            $em->flush();
            return $this->redirectToRoute('app_admin_soaps');
        }

        return $this->render('admin/soaps.html.twig', [
            'soaps' => $soaps,
            'form' => $form->createView(),
        ]);
    }


    //////////////////////////////////////////////////////////
    #[Route('/admin/users', name: 'admin_users')]
    public function listUsers(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }
    /////////////////////////////////////////////
    #[Route('/admin/user/{id}', name: 'admin_user_show')]
    public function show(User $user, ArticleRepository $articleRepo, CommentRepository $commentRepo): Response
    {
        $articles = $articleRepo->findBy(['user' => $user]);
        $comments = $commentRepo->findBy(['user' => $user]);

        return $this->render('admin/user_show.html.twig', [
            'user' => $user,
            'articles' => $articles,
            'comments' => $comments,
        ]);
    }
    //////////////////////////////////
    #[Route('/admin/article/delete/{id}', name: 'admin_delete_article')]
    public function deleteArticle(int $id, ArticleRepository $articleRepository, EntityManagerInterface $em): Response
    {
        $article = $articleRepository->find($id);
        if ($article) {
            $em->remove($article);
            $em->flush();
        }

        return $this->redirectToRoute('admin_users');
    }
    //////////////////////////////////

    #[Route('/admin/comment/delete/{id}', name: 'admin_delete_comment')]
    public function deleteComment(int $id, CommentRepository $commentRepository, EntityManagerInterface $em): Response
    {
        $comment = $commentRepository->find($id);
        if ($comment) {
            $em->remove($comment);
            $em->flush();
        }

        return $this->redirectToRoute('admin_users');
    }
    /////////////////////////
    #[Route('/admin/user/delete/{id}', name: 'admin_delete_user')]
    public function deleteUser(int $id, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $user = $userRepository->find($id);
        if ($user) {
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('admin_users');
    }
}
