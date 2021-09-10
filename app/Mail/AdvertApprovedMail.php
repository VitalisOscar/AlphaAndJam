<?php

namespace App\Mail;

use App\Models\Advert;
use App\Models\Invoice;
use App\Models\User;
use App\Repository\InvoiceRepository;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdvertApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    private $user, $invoice, $advert;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param Invoice $invoice
     * @param Advert $advert
     * @return void
     */
    public function __construct($user, $invoice, $advert)
    {
        $this->user = $user;
        $this->invoice = $invoice;
        $this->advert = $advert;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pdf_name = 'Invoice_'.$this->invoice->number.'.pdf';

        $pdf = PDF::loadView('docs.invoice_tabled', [
                'invoice' => $this->invoice
            ]);

        $output = $pdf->output();

        return $this
            ->view('emails.advert_approved')
            ->with([
                'invoice' => $this->invoice,
                'user' => $this->user,
                'advert' => $this->advert,
            ])
            ->attachData($output, $pdf_name)
            ->to($this->user->email)
            ->subject('Advert content approved');

    }
}
