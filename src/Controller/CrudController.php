<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Post;

class CrudController extends AbstractController
{
    /**
     * @Route("/crud", name="crud")
     */
    public function index()
    {
        return $this->render('crud/index.html.twig', [
            'controller_name' => 'CrudController',
        ]);
    }

    /**
     * @Route("/create", name="create_new_post")
     */
    public function createPost(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $post = new Post();
        $post->setTitle('Post Name');
        $post->setContent('Content Post');
        $post->setSlug('Slug Post');
        $post->setDisplay(true);
        $post->setPublished(new \DateTime("2019/11/21"));

        // tell Doctrine to (eventually) save the Post (no queries yet)
        $entityManager->persist($post);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Post Created ');
    }

    /**
     * @Route("/post/{id}", name="display_post")
     */
    public function displayPost(int $id)
    {
        $repository = $this->getDoctrine()->getRepository(Post::class);

        $post = $repository->find($id);

        return $this->render('crud/displayPost.html.twig', [
            'idPost' => $post->getTitle()
        ]);
    }

    /**
     * @Route("/post/edit/{id}", name="edit_post")
     */
    public function editPost(int $id)
    {

	    $entityManager = $this->getDoctrine()->getManager();
	    $post = $entityManager->getRepository(Post::class)->find($id);

	    if (!$post) {
	        throw $this->createNotFoundException(
	            'No post found for id '.$id
	        );
	    }

	    $post->setTitle('Update post name!');
	    $entityManager->flush();

	    return $this->redirectToRoute('display_post', [
	        'id' => $post->getId()
	    ]);
	}
        

    /**
     * @Route("/post/delete/{id}", name="delete_post")
     */
    public function deletePost(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
	    $post = $entityManager->getRepository(Post::class)->find($id);

	    if (!$post) {
	        throw $this->createNotFoundException(
	            'No post found for id '.$id
	        );
	    }

	    $entityManager->remove($post);
		$entityManager->flush();

		return new Response('Post deleted ');

    }
}
