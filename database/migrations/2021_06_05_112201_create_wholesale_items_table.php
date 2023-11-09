<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWholesaleItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wholesale_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('quantity');
            $table->enum('unit', ['Unit', 'Set']);
            $table->integer('purchase_price');
            $table->integer('selling_price');
            $table->string('category');
            $table->string('supplier');
            $table->string('photo');
            $table->unsignedBigInteger('wholesale_id');

            $table->foreign('wholesale_id')->references('id')->on('wholesales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wholesale_items');
    }
}
