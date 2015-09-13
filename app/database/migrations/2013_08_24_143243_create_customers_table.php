<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customers', function(Blueprint $table) {
			$table->increments('id');
			$table->string('token')->unique();
			$table->string('name');
			$table->string('phone')->unique();
			$table->string('email');
			$table->string('birthday');
			$table->integer('mode_id'); //Means retail/wholesale/distributor/major-distributor
			$table->string('createdby');
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
		Schema::drop('customers');
	}

}
