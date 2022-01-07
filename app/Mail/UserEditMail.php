<?php

namespace App\Mail; 


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserEditMail extends Mailable
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
        if($this->usereditmail['password'] != null){
            $this->usereditmail['msg'] = 'Password Updation';
        }else if($this->usereditmail['subscriptionplan'] != ''){
            $this->usereditmail['msg'] ='Subscription Plan Updation';
        }else{
            $this->usereditmail['msg'] ='User Detail Updation';
        }

        $mail = $this->view('planmail.user-edit-mail');
        $mail = $mail->from($address = 'testing@colanapps.in', $name = 'Kaizen Hub');
        $mail = $mail->subject($this->usereditmail['msg']);
        return $mail;
    }
}
