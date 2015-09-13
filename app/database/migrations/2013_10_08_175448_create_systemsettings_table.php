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
			$table->string('name')->default('Company');
			$table->string('registerednumber')->nullable();
			$table->string('address')->nullable();
			$table->string('phone')->nullable();
			$table->string('email')->nullable();
			$table->string('website')->nullable();
			$table->integer('dob_alert_day')->default(10);
			$table->string('paymentmode')->default('Cash, Pos, Coupon');
			$table->integer('receipt_paperwidth')->default(114);
			$table->string('receipt_note')->nullable();
			$table->string('receipt_note_alignment', 10)->default('left');
			$table->string('receipt_note_fontsize', 10)->default('inherit');
			$table->string('receipt_footer')->nullable();
			$table->string('receipt_footer_alignment', 10)->default('left');
			$table->string('receipt_footer_fontsize', 10)->default('inherit');
			$table->boolean('show_salesperson')->default(1);
			$table->boolean('show_customerdetails')->default(1);
			$table->boolean('show_paymentmode')->default(1);
			$table->integer('vat')->default(0);
			$table->nullableTimestamps();
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
