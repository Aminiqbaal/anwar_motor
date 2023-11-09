<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('amount');
            $table->integer('remaining');
            $table->enum('percentage', ['10', '20', '30', '40', '50']);
            $table->text('info');
            $table->enum('is_approved', ['-1', '0', '1'])->default('0');
            $table->tinyInteger('is_paid')->default('0');
            $table->date('created_at');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('workshop_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('workshop_id')->references('id')->on('workshops')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
