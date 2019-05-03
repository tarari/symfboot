<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Knp\Bundle\PaginatorBundle\Pagination;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(Request $request,PaginatorInterface $paginator)
    {
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
            2
        );
        foreach ($posts as $post){
            if($post->getSummary()==""){
                $post->setSummary($post->addSummary());
            }
        }
        return $this->render('home/index.html.twig',['posts'=>$posts]);
    }
    /**
     * @Route("/search", name="app_blog_search")
     * @Method("GET")
     */
    public function search(Request $request, PostRepository $posts)
    {


        $query = $request->query->get('q', '');
        $limit = $request->query->get('l', 10);
        $foundPosts = $posts->findBySearchQuery($query, $limit);

        $results = [];
        foreach ($foundPosts as $post) {

            $results[] = [
                'title' => htmlspecialchars($post->getTitle()),
                'date' => $post->getCreatedAt()->format('d M, Y'),
                'author' => htmlspecialchars($post->getUser()),
                'url' => $this->generateUrl('app_post_showc', ['id' => $post->getId()]),
            ];
        }

        return $this->json($results);
    }
}
