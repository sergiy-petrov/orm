<?php

namespace LaravelDoctrineTest\ORM\Assets\Notifications;

class NotificationDatabaseStub extends \Illuminate\Notifications\Notification
{
    public function toDatabase()
    {
        return (new \LaravelDoctrine\ORM\Notifications\Notification());
    }
}
