<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RoleUserMail extends Mailable
{
    use Queueable, SerializesModels;
    public $roleuserdetail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($roleuserdetail)
    {
        $this->roleuserdetail = $roleuserdetail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->view('roleuserdetail');
        $mail = $mail->from($address = 'testing@colanapps.in', $name = 'Kaizen Hub');
        $mail = $mail->subject('User Registration Confirmation');
        return $mail;
   }
}
