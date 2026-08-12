<?php

namespace App\Mail;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LicenseKeyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Transaction $transaction,
        public User $user
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[GameVault] Lisensi Game Kamu - Order #' . $this->transaction->order_id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.license-key',
        );
    }
}
