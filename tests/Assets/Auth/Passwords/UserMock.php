<?php

namespace LaravelDoctrineTest\ORM\Assets\Auth\Passwords;

use Illuminate\Contracts\Auth\CanResetPassword;

class UserMock implements CanResetPassword
{
    /**
     * Get the e-mail address where password reset links are sent.
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return 'user@mockery.mock';
    }

    /**
     * Send the password reset notification.
     *
     * @param  string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
    }
}
