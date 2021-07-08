<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PostController extends AbstractController
{
    #[Route('/', name: 'post_index', methods: ['GET'])]
    public function index(Request $request, PostRepository $postRepository): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = $this->getParameter('page_limit');
        $offset = ($page - 1) * $limit;
        $paginator = $postRepository->getPostPaginator($offset, $limit);
        $max_page = ceil($paginator->count() / $limit);
        return $this->render('post/index.html.twig', [
            'max_page' => $max_page,
            'paginator' => $paginator,
            'page' => $page
//            'posts' => $postRepository->findBy(['status' => 'published'], ['id' => 'DESC']),
        ]);
    }

    #[Route('/post/{id1}', name: 'post_show', methods: ['GET', 'POST'])]
    #[ParamConverter('post', options: ['id' => 'id1'])]
    public function show(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $commentForm = $this->createForm(CommentType::class);

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            if ($commentForm->get('submit')->isClicked()) {
                /**@var Comment $data * */
                $data = $commentForm->getData();
                $data->setPost($post);
                $entityManager->persist($data);
                $entityManager->flush();
            }
        }

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'comment_form' => $commentForm->createView()
        ]);
    }

}
