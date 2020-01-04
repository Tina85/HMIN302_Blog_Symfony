<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Post;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {

    	//get posts
    	$repository = $this->getDoctrine()->getRepository(Post::class);

        //$posts = $repository->findAll();
        $posts = $paginator-> paginate(
            $repository->findAllVisibleQuery(), 
            $request->query->getInt('page', 1),
            6);

        return $this->render('blog/index.html.twig', [
            'posts' => $posts
        ]);
    }
}
