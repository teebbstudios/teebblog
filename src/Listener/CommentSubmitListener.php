<?php


namespace App\Listener;


use App\Event\AfterCommentSubmitEvent;

class CommentSubmitListener
{
    public function onCommentSubmit(AfterCommentSubmitEvent $event)
    {
//        $comment = $event->getComment();
        $comment = $event->getSubject();
        $message = $comment->getMessage();
        $comment->setMessage(strip_tags($message));
        $event->stopPropagation();
    }
}