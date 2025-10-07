<?php

namespace App\Events;

use App\Models\Checkout;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CheckoutCreated
{
    use Dispatchable, SerializesModels;

    public $checkout;

    public function __construct(Checkout $checkout)
    {
        $this->checkout = $checkout;
    }
}
