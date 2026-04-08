<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\MicroPost;
use App\Form\CommentType;
use App\Form\MicroPostType;
use App\Repository\CommentsRepository;
use App\Repository\MicroPostRepository;
use Datetime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MicroPostController extends AbstractController
{
    #[Route('/micro-post/', name: 'app_micro_post')]
    public function index(MicroPostRepository $posts, EntityManagerInterface $entityManager,): Response
    {
        // Creacion de Posts
        // // // $microPost = new MicroPost();
        // // // $microPost->setTitle('It comes from controller');
        // // // $microPost->setText('Hello');
        // // // $microPost->setCreated(new DateTime());
        // $entityManager->persist($microPost);
        // $entityManager->flush();

        // Modificar query
        // $microPost = $posts->find(4);
        // $microPost->setTitle('Welcome in general');
        // $entityManager->persist($microPost);
        // $entityManager->flush();

        // Eliminar query
        // $microPost = $posts->find(4);
        // $entityManager->remove($microPost);
        // $entityManager->flush();

        // dd($posts->findAll());
        return $this->render('micro_post/index.html.twig', [
            'posts' => $posts->findAllWithComments(),
        ]);
    }
    #[Route('/micro-post/{id}', name: 'app_micro_post_show')]
    public function showOne(MicroPost $post): Response
    {
        // dd($post);
        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/micro-post/add', name: 'app_micro_post_add', priority: 2)]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new MicroPost();
        $form = $this->createForm(MicroPostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setCreated(new DateTime());
            $post->setAuthor($this->getUser());
            $entityManager->persist($post);
            $entityManager->flush();

            //Add messages
            $this->addFlash('success', 'Registro creado');
            //Redirection
            return $this->redirectToRoute('app_micro_post');
        }

        return $this->render(
            'task/add.html.twig',
            [
                'form' => $form,
                'post' => $post
            ]
        );
    }

    #[Route('/micro-post/{post}/edit', name: 'app_micro_post_edit')]
    public function edit(MicroPost $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MicroPostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $entityManager->persist($post);
            $entityManager->flush();

            // Add a flash
            $this->addFlash('success', 'Your micro post have been updated.');

            return $this->redirectToRoute('app_micro_post');
            // Redirect
        }

        return $this->render(
            'task/edit.html.twig',
            [
                'form' => $form,
                'post' => $post
            ]
        );
    }

    #[Route('/micro-post/{post}/comment', name: 'app_micro_post_comment')]
    public function addComment(MicroPost $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentType::class, new Comments());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setPost($post);
            $comment->setAuthor($this->getUser());
            $entityManager->persist($comment);
            $entityManager->flush();

            // Add a flash
            $this->addFlash('success', 'Your comment have been updated.');

            return $this->redirectToRoute(
                'app_micro_post_show',
                ['id' => $post->getId()]
            );
            // Redirect
        }

        return $this->render(
            'micro_post/comment.html.twig',
            [
                'form' => $form,
                'post' => $post
            ]
        );
    }
}
