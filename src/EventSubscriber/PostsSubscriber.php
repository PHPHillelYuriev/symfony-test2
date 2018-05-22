<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PostsSubscriber implements EventSubscriberInterface
{
    public function onShowTags($event)
    {
        // ...
    }

    public static function getSubscribedEvents()
    {
        return [
           'showTags' => 'onShowTags',
        ];
    }
}
