<?php

namespace App\EventSubscriber;

use App\Entity\Post;
use App\Message\SendEmailMessage;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use \EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\Email;

class SendEmailSubscrberSubscriber implements EventSubscriberInterface
{
//    /**
//     * @var ParameterBagInterface
//     */
//    private ParameterBagInterface $parameterBag;
//    /**
//     * @var MailerInterface
//     */
//    private MailerInterface $mailer;
//
//    public function __construct(ParameterBagInterface $parameterBag, MailerInterface $mailer)
//    {
//        $this->parameterBag = $parameterBag;
//        $this->mailer = $mailer;
//    }

    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function onAfterEntityPersistedEvent(AfterEntityPersistedEvent $event)
    {
        $post = $event->getEntityInstance();
        if ($post instanceof  Post)
        {
//            $email = (new Email())
//                ->from($this->parameterBag->get('send_email'))
//                ->to($this->parameterBag->get('editor_email'), $this->parameterBag->get('checker_email'))
//                ->subject('有新的文章<'.$post->getTitle().'>发布了，请检查。')
//                ->text('有新的文章<'.$post->getTitle().'>发布了，请检查。');
//sleep(10);
//            $this->mailer->send($email);
            $message = new SendEmailMessage($post->getId());
            $this->messageBus->dispatch($message);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            AfterEntityPersistedEvent::class => 'onAfterEntityPersistedEvent',
        ];
    }
}
