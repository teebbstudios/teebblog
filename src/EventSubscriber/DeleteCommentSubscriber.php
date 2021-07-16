<?php

namespace App\EventSubscriber;

use App\Entity\Comment;
use App\Entity\FileManaged;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityDeletedEvent;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use \EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;

class DeleteCommentSubscriber implements EventSubscriberInterface
{
    /**
     * @var FileManaged[]
     */
    private array $needDeletedFiles = [];
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
//    /**
//     * @var ParameterBagInterface
//     */
//    private ParameterBagInterface $parameterBag;
    private $projectDir;

    public function __construct(EntityManagerInterface $em, $projectDir)
    {
        $this->em = $em;
//        $this->parameterBag = $parameterBag;
        $this->projectDir = $projectDir;
    }

    public function onBeforeEntityDeletedEvent(BeforeEntityDeletedEvent $event)
    {
        $comment = $event->getEntityInstance();
        if ($comment instanceof Comment) {
            $files = $comment->getFiles();
            foreach ($files as $file){
                $this->needDeletedFiles[] = $file;
            }
        }
    }

    public function onAfterEntityDeletedEvent(AfterEntityDeletedEvent $event)
    {
//        $projectDir = $this->parameterBag->get('project_dir');

        foreach ($this->needDeletedFiles as $deletedFile){
            $this->em->remove($deletedFile);

            unlink($this->projectDir . '/public/' . $deletedFile->getPath());
        }
        $this->em->flush();
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityDeletedEvent::class => 'onBeforeEntityDeletedEvent',
            AfterEntityDeletedEvent::class => 'onAfterEntityDeletedEvent',
        ];
    }
}
