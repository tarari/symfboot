<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Post;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\PostEditType;

use App\Form\UserEditType;

/**
 * Class AdminController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{

    /**
     * @Route("/admin/post",name="app_admin_posts")
     */
    public function posts(Request $request, PaginatorInterface $paginator){
        //list all posts in a table
        $em=$this->getDoctrine()->getManager();
        $postRepository=$em->getRepository(Post::class);
        $allPostsQuery = $postRepository->createQueryBuilder('p')
            ->getQuery();
        $posts = $paginator->paginate(
        // Doctrine Query, not results
            $allPostsQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            3
        );
        return $this->render('admin/posts.html.twig',['posts'=>$posts]);

    }

    /**
     * @param Request $request
     * @param Post $post
     * @Route("/admin/post/{id}/edit",name="app_admin_post_edit")
     */
    public function editPost(Request $request,Post $post){
        //form edit user
        $form=$this->createForm(PostEditType::class,$post);
        $post=$form->getData();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setModifiedAt(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
            return $this->redirectToRoute('app_admin_posts');
        }
        return $this->render('admin/post_edit.html.twig',[
            'form'=>$form->createView(),
            'post'=>$post
        ]);
    }
    /**
     * @Route("/admin/user",name="app_admin_users")
     */
    public function users(Request $request,PaginatorInterface $paginator){
        //list all users in a table
        //$users=$this->getDoctrine()->getRepository(User::class)->findAll();
        $em=$this->getDoctrine()->getManager();
        $userRepository=$em->getRepository(User::class);
        $allUsersQuery = $userRepository->createQueryBuilder('u')
            ->getQuery();
        $users = $paginator->paginate(
        // Doctrine Query, not results
            $allUsersQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            2
        );
        return $this->render('admin/users.html.twig',[
            'users'=>$users
        ]);
    }
    /**
     * @Route("/admin/user/{id}/edit",name="app_admin_user_edit")
     *
     */
    public function editUser(Request $request, User $user,  UserPasswordEncoderInterface $passwordEncoder)
    {
        //form edit user
        $form = $this->createForm(UserEditType::class, $user);
        $user = $form->getData();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getPlainPassword()) {
                $password = $passwordEncoder->encodePassword($user, $user->getplainPassword());
                $user->setPassword($password);
            }
            //upload file
            $file = $user->getAvatar();
            if ($file) {
                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
                // moves the file to the directory where brochures are stored
                $file->move(
                    $this->getParameter('pictures_directory'),
                    $fileName
                );
                //updates property to store picture
                $user->setAvatar($fileName);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email
            return $this->redirectToRoute('app_admin_users');
        }
        return $this->render('admin/user_edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/admin/post/{id}/remove",name="app_admin_post_remove")
     */
    public function removePost(Post $post){
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
        $entityManager->flush();
        return $this->redirectToRoute("app_admin_posts");
    }

    /**
     * @Route("/admin/user/{id}/remove",name="app_admin_user_remove")
     */
    public function removeUser(User $user){
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute("app_admin_users");
        }
    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}
