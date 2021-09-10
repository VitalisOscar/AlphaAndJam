<?php

namespace App\Http\Controllers\Admin\Billing;

use App\Exports\ExcelExport;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\StaffLog;
use App\Repository\InvoiceRepository;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InvoicesController extends Controller
{
    function getAll(InvoiceRepository $repository){
        return response()->view('admin.billing.invoices', $repository->adminFetch());
    }

    function getSingle(InvoiceRepository $repository, $number){
        $invoice = $repository->adminSingle($number);

        return response()->view('admin.billing.single_invoice', [
            'invoice' => $invoice,
            'user' => $invoice->advert->user
        ]);
    }

    function confirmPayment(Request $request, $number){
        $invoice = Invoice::where('number', $number)->first();

        $payment = new Payment([
            'invoice_id' => $invoice->id,
            'method' => $request->post('method'),
            'code' => $request->post('code'),
            'generated' => 'admin',
            'status' => Payment::STATUS_SUCCESSFUL
        ]);

        // Staff log
        $staff = auth('staff')->user();
        $log = new StaffLog([
            'staff_id' => $staff->id,
            'activity' => "Marked invoice '#".$invoice->number."' as paid",
            'item' => StaffLog::ITEM_INVOICE,
            'item_id' => $invoice->id
        ]);

        if($payment->save() && $log->save()){
            return back()->with([
                'status' => 'The invoice has been marked as paid'
            ]);
        }

        return back()->withErrors([
            'status' => 'Something went wrong. Please try again'
        ]);
    }

    function export(InvoiceRepository $repository){
        $filename = 'invoices_'.str_replace('-', '_', Carbon::now()->format('Y-m-d')).'.pdf';

        return Excel::download(new ExcelExport($repository->getQuery(), Invoice::exportHeaders()), $filename);
    }

    function download(InvoiceRepository $repository, $invoice_number){
        $invoice = $repository->adminSingle($invoice_number);

        $pdf_name = 'Invoice_'.$invoice->number.'.pdf';

        $pdf = PDF::loadView('docs.invoice_tabled', [
            'invoice' => $invoice
        ]);

        return $pdf->stream($pdf_name, array("Attachment" => false));;
    }
}
