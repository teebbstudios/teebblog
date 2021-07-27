<?php

namespace App\MessageHandler;

use App\Message\SendEmailMessage;
use App\Repository\PostRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

final class SendEmailMessageHandler implements MessageHandlerInterface
{
    /**
     * @var PostRepository
     */
    private PostRepository $postRepository;
    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    public function __construct(PostRepository $postRepository, ParameterBagInterface $parameterBag, MailerInterface $mailer)
    {
        $this->postRepository = $postRepository;
        $this->parameterBag = $parameterBag;
        $this->mailer = $mailer;
    }

    public function __invoke(SendEmailMessage $message)
    {
        $postId = $message->getPostId();
        $post = $this->postRepository->find($postId);

        $email = (new Email())
            ->from($this->parameterBag->get('send_email'))
            ->to($this->parameterBag->get('editor_email'), $this->parameterBag->get('checker_email'))
            ->subject('有新的文章<'.$post->getTitle().'>发布了，请检查。')
            ->text('有新的文章<'.$post->getTitle().'>发布了，请检查。');
        sleep(10);
        $this->mailer->send($email);
    }
}
