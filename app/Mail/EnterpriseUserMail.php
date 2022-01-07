<?php

namespace App\Mail; 


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnterpriseUserMail extends Mailable
{
    use Queueable, SerializesModels;
     public $usereditmail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($usereditmail)
    {
          $this->usereditmail = $usereditmail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {       
        $mail = $this->view('planmail.enterpriseUserMail');
        $mail = $mail->from($address = 'testing@colanapps.in', $name = 'Kaizen Hub');
        if($this->usereditmail['status'] == 1){
            $mail = $mail->subject('Enterprise User Request Approved');
        }else{
            $mail = $mail->subject('Enterprise User Request Rejected');
        }
        return $mail;
    }
}
