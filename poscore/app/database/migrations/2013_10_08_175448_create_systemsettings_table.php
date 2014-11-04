<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSystemsettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('systemsettings', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('registerednumber');
			$table->string('address');
			$table->string('phone');
			$table->string('email');
			$table->string('website');
			$table->string('dob_alert_day');
			$table->string('paymentmode');
			$table->integer('receipt_paperwidth')->default(114);
			$table->string('receipt_note');
			$table->string('receipt_note_alignment', 10)->default('left');
			$table->string('receipt_note_fontsize', 10)->default('inherit');
			$table->string('receipt_footer');
			$table->string('receipt_footer_alignment', 10)->default('left');
			$table->string('receipt_footer_fontsize', 10)->default('inherit');
			$table->boolean('show_salesperson')->default(1);
			$table->boolean('show_customerdetails')->default(1);
			$table->boolean('show_paymentmode')->default(1);
			$table->integer('vat');
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
		Schema::drop('systemsettings');
	}

}
