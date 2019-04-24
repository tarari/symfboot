<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class PostController extends AbstractController
{
    /**
     * @Route("/post", name="app_post")
     */
    public function index()
    {
        $em=$this->getDoctrine()->getManager();
        $em->getRepository(Post::class);
        return $this->render('post/index.html.twig');
    }

    /**
     * @Route("/post/new",name="app_post_new")
     *
     */
    public function new(Request $request){
        $post=new Post();
        $user=$this->getUser();
        $post->setAuthor($user);
        $post->setUser($user);

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post=$form->getData();
            //if($form->getData())
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('app_home');
        }
        return $this->render('post/new.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @Route("/post/edit/{id}",name="app_post_edit")
     */
    public function edit(Request $request,$id){
        //fetch object
        $entityManager = $this->getDoctrine()->getManager();
        $post = $entityManager->getRepository(Post::class)->find($id);

        //modify object through the form
        $form = $this->createForm(PostType::class, $post);
        $form->setData($post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $post=$form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('app_home');
        }
        return $this->render('post/edit.html.twig', [
            'form' => $form->createView()
        ]);

        //flush object
    }

    /**
     * @param $id
     * @Route("/post/{id}",name="app_post_show")
     */
    public function show($id){
        $entityManager = $this->getDoctrine()->getManager();
        $post = $entityManager->getRepository(Post::class)->find($id);

        return $this->render('post/show.html.twig',[
            'post'=>$post
        ]);
    }

}
