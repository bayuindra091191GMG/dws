<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $url)
    {
        //
        $this->user = $user;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $urlStr = url($this->url.'/verifyemail/'.$this->user->email_token);
        return $this->subject($this->user->first_name. " ".$this->user->last_name.", Aktivasi Akun Anda di Digital Waste Solution")
            ->view('mail.userverification')->with([
                'url_str' => $urlStr,
            ]);
    }
}
