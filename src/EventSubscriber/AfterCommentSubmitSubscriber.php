<?php

namespace App\EventSubscriber;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Event\AfterCommentSubmitEvent;

class AfterCommentSubmitSubscriber implements EventSubscriberInterface
{
    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function onAfterCommentSubmitEvent(AfterCommentSubmitEvent $event)
    {
        if ($event->isPropagationStopped()){
            return;
        }
        $words = $this->parameterBag->get('strip_words');

//        $comment = $event->getComment();
        $comment = $event->getSubject();
        $message = $comment->getMessage();

        $comment->setMessage($this->stripWords($message, $words));
    }

    public static function getSubscribedEvents()
    {
        return [
            AfterCommentSubmitEvent::class => 'onAfterCommentSubmitEvent',
        ];
    }

    private function stripWords(string $message, array $words)
    {
        foreach ($words as $word){
            $message = str_replace($word, ' *** ', $message);
        }

        return $message;
    }
}
