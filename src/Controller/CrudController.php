<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\Type\PostType;
use App\Entity\Post;

class CrudController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_posts")
     */
    public function index()
    {
        //get all posts
        $repository = $this->getDoctrine()->getRepository(Post::class);
        $posts = $repository->findAll();
        return $this->render('crud/index.html.twig', [
            'posts' => $posts            
        ]);
    }

    /**
     * @Route("/admin/post/new", name="new_post")
     */
    public function createPost(Request $request)
    {
        $post = new Post();

        /*$post->setTitle('Post Name');
        $post->setContent('Content Post');
        $post->setSlug('Slug Post');
        $post->setDisplay(true);
        $post->setPublished(new \DateTime("2019/11/21"));
        */
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setPublished(new \DateTime('now'));
            $post->setDisplay(true);
            if($post->getFilename()==null){
                $post->setFilename("no_img.jpg");
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('created','Post crée avec succès!');

            return $this->redirectToRoute('admin_posts');
        }

        return $this->render('crud/newPost.html.twig', [
            'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/post/{slug}", name="display_post")
     */
    public function displayPost($slug)
    {
        $repository = $this->getDoctrine()->getRepository(Post::class);

        $post = $repository->findOneBy(['slug' => $slug]);

        return $this->render('crud/displayPost.html.twig', [
            'post' => $post
        ]);
    }

    /**
     * @Route("/admin/post/edit/{id}", name="edit_post", requirements={"id"="\d+"})
     */
    public function editPost(int $id, Request $request)
    {

	    $entityManager = $this->getDoctrine()->getManager();
	    $post = $entityManager->getRepository(Post::class)->find($id);

	    if (!$post) {
	        throw $this->createNotFoundException(
	            'No post found for id '.$id
	        );
	    }

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setUpdated(new \DateTime('now'));
            $post->setDisplay(true);
            if($post->getFilename()==null){
                $post->setFilename("no_img.jpg");
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('updated','Post modifié avec succès!');

            return $this->redirectToRoute('admin_posts');
        }

        
        return $this->render('crud/editPost.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);



	    $post->setTitle('Update post name !');
	    $entityManager->flush();

	    return $this->redirectToRoute('display_post', [
	        'slug' => $post->getSlug()
	    ]);
	}
        

    /**
     * @Route("/admin/post/delete/{id}", name="delete_post")
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

        $this->addFlash('deleted', 'Le post a été supprimé avec succès!');
        return $this->redirectToRoute('admin_posts');

    }
}
