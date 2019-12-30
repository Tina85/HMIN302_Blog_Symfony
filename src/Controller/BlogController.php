<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Post;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
    	//get posts
    	$repository = $this->getDoctrine()->getRepository(Post::class);

        $posts = $repository->findAll();

        return $this->render('blog/index.html.twig', [
            'posts' => $posts
        ]);
    }
}
