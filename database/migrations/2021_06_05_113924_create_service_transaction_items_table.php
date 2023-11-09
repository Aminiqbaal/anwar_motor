<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceTransactionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_transaction_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('transaction_id');

            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_transaction_items');
    }
}
