<?php

namespace LaravelDoctrineTest\ORM\Assets\Notifications;

class NotificationStub extends \Illuminate\Notifications\Notification
{
    public function toEntity()
    {
        return (new \LaravelDoctrine\ORM\Notifications\Notification());
    }
}
