<?php

namespace App\Message;

final class SendEmailMessage
{
    /*
     * Add whatever properties & methods you need to hold the
     * data for this message class.
     */

     private $postId;
//
     public function __construct(string $postId)
     {
         $this->postId = $postId;
     }
//
    public function getPostId(): string
    {
        return $this->postId;
    }
}
