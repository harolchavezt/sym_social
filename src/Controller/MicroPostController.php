<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(MicroPostRepository $posts, EntityManagerInterface $entityManager): Response
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

        dd($posts->findAll());
        return $this->render('micro_post/index.html.twig', [
            'controller_name' => 'MicroPostController',
        ]);
    }
    #[Route('/micro-post/{id}', name: 'app_micro_post_show')]
    public function showOne(MicroPost $post): Response
    {
        dd($post);
    }
}
