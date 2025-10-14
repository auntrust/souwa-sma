<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SeminarReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public $seminar, public $customer) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: '【明日開催】' . $this->seminar->name . ' のお知らせ');
    }

    public function content(): Content
    {
        return new Content(
            html: 'emails.seminar_reminder',
            text: 'emails.seminar_reminder_plain',
            with: [
                'seminar' => $this->seminar,
                'customer' => $this->customer,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
