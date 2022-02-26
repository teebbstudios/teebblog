<?php

namespace App\EventSubscriber;

use App\Entity\SimpleFile;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use \Teebb\UploaderBundle\Event\AfterFileObjectSetPropertyEvent;

class SimpleFileSubscriber implements EventSubscriberInterface
{
    public function onAfterFileObjectSetPropertyEvent(AfterFileObjectSetPropertyEvent $event)
    {
        /**@var UploadedFile $uploadedFile * */
        $uploadedFile = $event->getUploadedFile();
        /**@var SimpleFile $simpleFile * */
        $simpleFile = $event->getFileObject();

//        $simpleFile->setPath('teebb_uploader' . \DIRECTORY_SEPARATOR. $simpleFile->getFileName() );
    }

    public static function getSubscribedEvents()
    {
        return [
            AfterFileObjectSetPropertyEvent::class => 'onAfterFileObjectSetPropertyEvent',
        ];
    }
}
