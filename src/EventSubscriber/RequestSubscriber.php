<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Psr\Log\LoggerInterface;


class RequestSubscriber implements EventSubscriberInterface
{
    // public function onKernelRequest(GetResponseEvent $event, LoggerInterface $logger)
    // {
    //     if (!$event->isMasterRequest()) {
    //         return;
    //     }

    //     $logger->info('Site visited a user');
    // }

    public static function getSubscribedEvents()
    {
        return [
           'kernel.request' => 'onKernelRequest',
        ];
    }

    //Если использую самописную функции то пишет log
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $this->writeLogUserVisits();
    }

    public function writeLogUserVisits()
    {   
        $path = "..\\var\log";

        $file = $path . "\\userVisits.txt";
    
        $text = "К нам зашел юзер \n";
    
        file_put_contents($file, $text, FILE_APPEND);
    }

}
