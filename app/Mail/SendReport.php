<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Pengguna;

class SendReport extends Mailable
{
    use Queueable, SerializesModels;

    public $reportData;
    public $latestUpdate;

    /**
     * Create a new message instance.
     */
    public function __construct($reportData, $latestUpdate)
    {
        $this->reportData = $reportData;
        $this->latestUpdate = $latestUpdate;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Progress Report TPH - ' . now()->format('d F Y'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'components.mailreport',
            with: [
                'latestUpdate' => $this->latestUpdate,
                'reportData' => $this->reportData,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build()
    {
        return $this->view('components.mailreport')
            ->subject('Progress Report TPH - ' . now()->format('d F Y'));
    }
}
