<?php

namespace App\Mail;

use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RefundApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public TransactionDetail $detail,
        public User $user
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[GameVault] Refund Kamu Disetujui',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.refund-approved',
        );
    }
}
