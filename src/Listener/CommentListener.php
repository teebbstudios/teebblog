<?php


namespace App\Listener;


use App\Entity\Comment;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

class CommentListener
{
    /** @ORM\PostLoad */
    public function postLoadHandler(Comment $comment, LifecycleEventArgs $event)
    {
        $rootComment = $this->getParent($comment);
        $comment->setPost($rootComment->getPost());
    }

    public function getParent(Comment $comment)
    {
        $parent = $comment->getParent();
        if ($parent) {
            return $this->getParent($parent);
        }

        return $comment;
    }

    /** @ORM\PreRemove */
    public function preRemoveHandler(Comment $comment, LifecycleEventArgs $event)
    {
        $this->fixCommentTitleAndLevelWhenRemove($comment);
    }

    public function fixCommentTitleAndLevelWhenRemove(Comment $comment)
    {
        $children = $comment->getChildren();
        foreach ($children as $child) {
            if ($post = $comment->getPost()) {
                $child->setPost($post);
            }
            $child->setLevel($comment->getLevel());
            if ($parent = $comment->getParent()) {
                $child->setParent($parent);
            }

            $this->fixCommentTitleAndLevelWhenRemove($child);
        }
    }
}