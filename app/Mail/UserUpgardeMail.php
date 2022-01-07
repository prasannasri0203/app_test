<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserUpgardeMail extends Mailable
{
    use Queueable, SerializesModels;
    public $userdetail;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userdetail)
    {
        $this->userdetail = $userdetail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //dd($this->userdetail);
        $mail = $this->view('planmail.renewalUserDetail');
        $mail = $mail->from($address = 'testing@colanapps.in', $name = 'Kaizen Hub');
        $mail = $mail->subject('Plan Renewal Details');
        return $mail;
    }
}


?>