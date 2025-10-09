<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CheckoutConfirmationMailPayment extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $teacher;
    public $course;
    public $checkout;

    public function __construct($student, $teacher, $course, $checkout)
    {
        $this->student  = $student;
        $this->teacher  = $teacher;
        $this->course   = $course;
        $this->checkout = $checkout;
    }

    public function build()
    {
        return $this->subject('Status da sua compra')
                    ->view('emails.checkout_confirmation_payment');
    }
}
