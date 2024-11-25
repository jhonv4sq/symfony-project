<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post_index', methods: ['GET'])]
    public function index(PostRepository $posts): Response
    {
        dd($posts->findAll());
        return $this->render('post/index.html.twig', [
            'posts' => $posts->findAll(),
        ]);
    }

    #[Route('/post/store', name: 'app_post_store', methods: ['POST'])]
    public function store(EntityManagerInterface $entityManager): Response
    {
        $response = array(
            'title' => 'new post',
            'type' => 'create',
            'description' => 'Lorem Ipsum es simplemente el texto de relleno de las imprentas...',
            'creation_date' => (new \DateTime())->format('Y-m-d H:i:s'),
        );

        $user = $entityManager->getRepository(User::class)->find(1);
        if (!$user) {
            return new Response('User not found', 404);
        }
    
        $post = new Post();
        $post->setTitle($response['title']);
        $post->setType($response['type']);
        $post->setContent($response['description']);
        $post->setCreateDate(new \DateTime($response['creation_date']));
        $post->setUser($user);

        $entityManager->persist($post);
        $entityManager->flush();

        return new Response('Post created successfully with ID: ' . $post->getId());
    }

    #[Route('/post/{id}', name: 'app_post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        dd($post);
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }
}
