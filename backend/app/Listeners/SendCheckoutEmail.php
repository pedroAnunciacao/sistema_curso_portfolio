<?php

namespace App\Listeners;

use App\Events\CheckoutCreated;
use App\Services\CheckoutEmailService;

class SendCheckoutEmail
{
    protected $emailService;

    public function __construct(CheckoutEmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function handle(CheckoutCreated $event)
    {
        $this->emailService->sendConfirmation($event->checkout);
    }
}
