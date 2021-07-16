<?php


namespace App\Event;


use App\Entity\Comment;
use Symfony\Component\EventDispatcher\GenericEvent;

class AfterCommentSubmitEvent extends GenericEvent
{
//    private Comment $comment;
//
//    public function __construct(Comment $comment)
//    {
//        $this->comment = $comment;
//    }
//
//    /**
//     * @return Comment
//     */
//    public function getComment(): Comment
//    {
//        return $this->comment;
//    }

}