<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewPortfolioInquiry extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array{id: string, name: string, email: string, company: string|null, project_type: string, message: string, submitted_at: string}  $inquiry
     */
    public function __construct(public array $inquiry) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [$this->inquiry['email']],
            subject: 'New project inquiry from DevTZ.com',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.inquiries.received',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
