<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSparepartTransactionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sparepart_transaction_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('quantity');
            $table->unsignedBigInteger('sparepart_id');
            $table->unsignedBigInteger('transaction_id');

            $table->foreign('sparepart_id')->references('id')->on('spareparts')->onDelete('cascade');
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
        Schema::dropIfExists('sparepart_transaction_items');
    }
}
