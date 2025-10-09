<?php

namespace App\Listeners;

use App\Events\CheckoutUpdateted;
use App\Services\CheckoutEmailService;

class SendCheckoutEmailPayment
{
    protected $emailService;

    public function __construct(CheckoutEmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function handle(CheckoutUpdateted $event)
    {
        $this->emailService->sendConfirmationStatus($event->checkout);
    }
}
