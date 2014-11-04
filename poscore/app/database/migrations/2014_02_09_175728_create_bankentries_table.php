<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBankentriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bankentries', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->string('bank_name');
			$table->string('teller_number');
			$table->string('payment_type');
			$table->string('amount');
			$table->string('depositor_name');
			$table->string('deposit_date');
			$table->string('depositor_number');
			$table->string('comment');
			$table->boolean('status');
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
		Schema::drop('bankentries');
	}

}
