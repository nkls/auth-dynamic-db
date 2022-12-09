<?php

namespace App\Listeners;

use App\Events\UserReset;
use App\Helpers\DataBase;
use App\Queue\ActiveMQ;
use Illuminate\Support\Facades\App;

class PushQueueEmailResetPassword
{
    public function handle(UserReset $event): void
    {
        ActiveMQ::send('org_send_email_reset_password',
            [
                'link' => route('user.onetime.hash', [
                    'org_uuid' => DataBase::getUUID(),
                    'hash' => $event->getHash()
                ])
            ]
        );
    }
}
