<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

class BaseController extends AbstractController
{
    public function addFlashMessages(string $type, $message, $parameters = [], $domain = null, $locale = null)
    {
//        dd($this->container);
        $translator = $this->container->get('translator');
        $this->addFlash($type, $translator->trans($message, $parameters, $domain, $locale));
    }

    public static function getSubscribedServices()
    {
        return array_merge(parent::getSubscribedServices(), [
            'translator' => '?'.TranslatorInterface::class
        ]);
    }
}