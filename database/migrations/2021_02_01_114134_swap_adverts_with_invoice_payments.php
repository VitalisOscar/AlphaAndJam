<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SwapAdvertsWithInvoicePayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mpesa_payments', function(Blueprint $table){
            $table->dropForeign('mpesa_payments_advert_id_foreign');
            $table->dropColumn('advert_id');
            $table->foreignId('invoice_id')->after('checkout_request_id')->constrained('invoices')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
