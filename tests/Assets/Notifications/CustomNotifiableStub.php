<?php

namespace LaravelDoctrineTest\ORM\Assets\Notifications;

use LaravelDoctrine\ORM\Notifications\Notifiable;

class CustomNotifiableStub
{
    use Notifiable;

    public function routeNotificationForDoctrine()
    {
        return 'custom';
    }
}
