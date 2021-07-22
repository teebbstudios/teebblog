<?php

namespace App\EventSubscriber;

use App\Entity\Post;
use App\Security\Voter\PostVoter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use \EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class PostPermissionSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function onBeforeCrudActionEvent(BeforeCrudActionEvent $event)
    {
        $adminContext = $event->getAdminContext();
        $instance = $adminContext->getEntity()->getInstance();
        $crud = $adminContext->getCrud();
        if ($instance instanceof Post) {
            if ($crud->getCurrentAction() == 'edit') {
                $result = $this->security->isGranted(PostVoter::POST_OWNER_EDIT, $instance);
                if (!$result){
                    throw new AccessDeniedException('只有文章作者才可以编辑当前文章');
                }
            } elseif ($crud->getCurrentAction() == 'delete') {
                $result = $this->security->isGranted(PostVoter::POST_OWNER_DELETE, $instance);
                if (!$result){
                    throw new AccessDeniedException('只有文章作者才可以删除当前文章');
                }
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeCrudActionEvent::class => 'onBeforeCrudActionEvent',
        ];
    }
}
