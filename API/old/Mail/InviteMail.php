<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Mail\Mailables\Content;
// use Illuminate\Mail\Mailables\Envelope;

class InviteMail extends Mailable {
    use Queueable, SerializesModels;
    public $mailData;
    /**
     * Create a new message instance.
     */
    public function __construct($mailData) {
        $this->mailData = $mailData;
    }
    public function build() {
        $mailContent = /*HTML*/<<<EOT
        <h2>{$this->mailData['title']}</h2>
        <p>{$this->mailData['body']}</p>
        <a href="http://localhost:3000/reset-password/{$this->mailData['token']}">Click here</a>
        EOT;

        return $this->subject($this->mailData['title'])
            ->html($mailContent);
    }
    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope {
    //     return new Envelope(
    //         subject: 'Invite Mail',
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  */
    // // public function content(): Content {
    // //     return new Content(
    // //         view: 'view.name',
    // //     );
    // // }

    // /**
    //  * Get the attachments for the message.
    //  *
    //  * @return array<int, \Illuminate\Mail\Mailables\Attachment>
    //  */
    // public function attachments(): array {
    //     return [];
    // }
}