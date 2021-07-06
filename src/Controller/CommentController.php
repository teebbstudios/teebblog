<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[
        Route('/post/{post_id}/comment/{comment_id}/reply',options: ['expose' => true], name: 'reply_comment'),
        ParamConverter('post', options: ['id' => 'post_id']),
        ParamConverter('parentComment', options: ['id' => 'comment_id']),
    ]
    public function replyComment(Request $request, Post $post, Comment $parentComment, EntityManagerInterface $em): Response
    {
        $maxLevel = $this->getParameter('max_comment_level');

        if ($parentComment->getLevel()==$maxLevel){
            return new Response('<p class="max-level-info">当前评论已达到最大层级，不允许添加子评论。😄️</p>');
        }

        $replyComment = $this->createForm(CommentType::class, null, [
            'action' => $request->getUri()
        ]);

        $replyComment->handleRequest($request);

        if ($replyComment->isSubmitted() && $replyComment->isValid()){
            /**@var Comment $data**/
            $data = $replyComment->getData();
            $data->setParent($parentComment);
            $data->setLevel($parentComment->getLevel() + 1);
//            $data->setPost($post);
            $em->persist($data);
            $em->flush();

            return $this->redirectToRoute('post_show', ['id1' => $post->getId()]);
        }

        return $this->render('comment/_reply_comment_form.html.twig', [
            'reply_comment_form' => $replyComment->createView(),
        ]);
    }
}
