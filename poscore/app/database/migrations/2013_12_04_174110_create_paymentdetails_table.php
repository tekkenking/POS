<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentdetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('paymentdetails', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('receipt_id');
			$table->string('method');
			$table->string('amount');
			$table->string('reference');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('paymentdetails');
	}

}
