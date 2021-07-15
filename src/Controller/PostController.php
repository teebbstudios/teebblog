<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\FileManaged;
use App\Entity\Post;
use App\Event\AfterCommentSubmitEvent;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
    public function show(Request $request, Post $post, EntityManagerInterface $entityManager,
                         PaginatorInterface $paginator, CommentRepository $commentRepository,
                         EventDispatcherInterface $eventDispatcher): Response
    {
        $commentForm = $this->createForm(CommentType::class);

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            if ($commentForm->get('submit')->isClicked()) {
                /**@var Comment $data * */
                $data = $commentForm->getData();
//                dd($data);
                $event = new AfterCommentSubmitEvent($data);
                /**@var AfterCommentSubmitEvent $modifiedEvent**/
                $modifiedEvent = $eventDispatcher->dispatch($event);
                $data = $modifiedEvent->getComment();
//                $files = $request->files->all();
//                /**@var UploadedFile $file**/
//                foreach ($files['comment']['files'] as $file){
//                    $originName = $file->getClientOriginalName();
//                    $fileName = pathinfo(htmlspecialchars($originName), PATHINFO_FILENAME) . '-' . $file->getFilename() . '.' . $file->getClientOriginalExtension();
//                    $uploadPath = $this->getParameter('base_path');
//                    $mimeType = $file->getMimeType();
//                    $filesize = $file->getSize();
//
//                    $file->move($uploadPath, $fileName);
//
//                    $fileManaged = new FileManaged();
//                    $fileManaged->setOriginName($originName);
//                    $fileManaged->setFileName($fileName);
//                    $fileManaged->setMimeType($mimeType);
//                    $fileManaged->setPath($uploadPath . '/' . $fileName);
//                    $fileManaged->setFileSize($filesize);
//
//                    $data->addFile($fileManaged);
//                }

//                dd($data);
                $data->setPost($post);
                $entityManager->persist($data);
                $entityManager->flush();
            }

            $this->addFlash('success', '您的评论已成功提交！');
        }

        $query = $commentRepository->getPaginationQuery($post);
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'pagination' => $pagination,
            'comment_form' => $commentForm->createView()
        ]);
    }

}
