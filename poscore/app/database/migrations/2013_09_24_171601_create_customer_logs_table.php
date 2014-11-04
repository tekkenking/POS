<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomerLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customer_logs', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('customer_id')->unique();
			$table->string('alltime_spent')->default(0);
			$table->integer('alltime_quantity')->default(0);
			$table->integer('alltime_visits')->default(0);
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
		Schema::drop('customer_logs');
	}

}
