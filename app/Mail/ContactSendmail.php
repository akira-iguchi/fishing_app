<?php

namespace App\Mail;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactSendmail extends Mailable
{
    use Queueable, SerializesModels;

    private $email;
    private $title;
    private $body;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->email = $request->email;
        $this->title = $request->title;
        $this->body  = $request->body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('akituri931@icloud.com')
            ->subject('自動送信メール')
            ->view('contact.mail')
            ->with([
                'email' => $this->email,
                'title' => $this->title,
                'body'  => $this->body,
            ]);
    }
}
