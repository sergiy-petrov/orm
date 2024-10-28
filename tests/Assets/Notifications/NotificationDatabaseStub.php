<?php

namespace LaravelDoctrineTest\ORM\Assets\Notifications;

use Illuminate\Notifications\Notification as IlluminateNotification;
use LaravelDoctrine\ORM\Notifications\Notification;

class NotificationDatabaseStub extends IlluminateNotification
{
    public function toDatabase()
    {
        return new Notification();
    }
}
