<?php

namespace App\Http\Controllers\User\Account;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Repository\InvoiceRepository;
use App\Services\PaymentService;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;


class InvoicesController extends Controller
{
    function getAll(InvoiceRepository $repository){
        $result = $repository->fetchAll();

        return response()->view('web.user.invoices', [
            'result' => $result,
        ]);
    }

    function getSingle(InvoiceRepository $repository, $invoice_number){
        $invoice = $repository->getSingle($invoice_number);

        return response()->view('web.user.single_invoice', [
            'invoice' => $invoice,
        ]);
    }

    function download(InvoiceRepository $repository, $invoice_number){
        $invoice = $repository->getSingle($invoice_number);

        $pdf_name = 'Invoice_'.$invoice->number.'.pdf';

        $pdf = PDF::loadView('docs.invoice_tabled', [
                'invoice' => $invoice
            ]);

        return $pdf->download($pdf_name);
    }

    function payment(InvoiceRepository $repository, PaymentService $paymentService, $invoice_number){
        $invoice = $repository->getSingle($invoice_number);

        return response()->view('web.ads.payment', [
            'invoice' => $invoice,
            'token' => $paymentService->getToken($invoice)
        ]);
    }
}
