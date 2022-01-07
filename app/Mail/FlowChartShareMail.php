<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FlowChartShareMail extends Mailable
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
        $subject = 'You are invited to the document '.$this->userdetail['chartname'];
        $mail = $this->view('planmail.flowchartShareDetail');
        $mail = $mail->from($address = 'testing@colanapps.in', $name = 'Kaizen Hub');
        $mail = $mail->subject($subject);
        return $mail;
    }
}


?>