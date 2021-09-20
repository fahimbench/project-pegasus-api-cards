<?php

namespace App\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class ImageTo64Listener implements EventSubscriberInterface
{
    private ?string $defaultProtocol;

    /**
     * @param string|null $defaultProtocol The URL scheme to add when there is none or null to not modify the data
     */
    public function __construct(?string $defaultProtocol = 'http')
    {
        $this->defaultProtocol = $defaultProtocol;
    }

    public function onSubmit(FormEvent $event)
    {
        try{
            $link = $event->getData();
            if ($this->defaultProtocol && $link && \is_string($link) && !preg_match('~^[\w+.-]+://~', $link)) {
                $link = $this->defaultProtocol.'://'.$link;
            }
            $data = $this->file_contents($link);
            $event->setData('data:image/jpg;base64,'.base64_encode($data));
        }catch(\Exception $exception){

        }
    }

    public static function getSubscribedEvents()
    {
        return [FormEvents::SUBMIT => 'onSubmit'];
    }

    /**
     * @throws \Exception
     */
    public function file_contents($path): string
    {
        $str = @file_get_contents($path);
        if ($str === FALSE) {
            throw new \Exception("Cannot access '$path' to read contents.");
        } else {
            return $str;
        }
    }
}
