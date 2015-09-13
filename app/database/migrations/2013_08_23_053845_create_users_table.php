<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('usertoken')->unique();
			$table->string('username')->unique();
			$table->string('password')->nullable();
			$table->string('image')->nullable();
			$table->string('name')->nullable();
			$table->string('gender')->nullable();
			$table->string('phone')->nullable();
			$table->string('email')->nullable();
			$table->string('birthday')->nullable();
			$table->string('houseaddress')->nullable();
			$table->string('guarantor_image')->nullable();
			$table->string('guarantor_name')->nullable();
			$table->string('guarantor_gender')->nullable();
			$table->string('guarantor_phone')->nullable();
			$table->string('guarantor_email')->nullable();
			$table->string('guarantor_address')->nullable();
			$table->string('guarantor_workplace_phone')->nullable();
			$table->string('guarantor_workplace_address')->nullable();
			$table->integer('isloggedin')->default(0);
			$table->string('loggedtime')->nullable();
			$table->boolean('isenabled')->default(1);
			$table->timestamp('last_active')->default('0000-00-00 00:00:00');
			$table->string('remember_token', 100)->nullable();
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
		Schema::drop('users');
	}

}
