<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReceiptsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('receipts', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('customer_id');
			$table->integer('user_id');
			
			$table->string('receipt_subtotalamount');
			$table->string('receipt_worth');
			$table->string('vat');
			$table->string('vat_worth');
			$table->string('amount_tendered');
			$table->string('change');
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
		Schema::drop('receipts');
	}

}
