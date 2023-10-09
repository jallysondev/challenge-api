<?php

namespace App\Listeners;

use App\Events\SendFailureSyncNotification;
use App\Mail\FailureSyncNotification;
use Illuminate\Support\Facades\Mail;

class SendEmailNotification
{
    public function handle(SendFailureSyncNotification $event): void
    {
        Mail::to(env('EMAIL_ADMIN'))
            ->send(new FailureSyncNotification());
    }
}
