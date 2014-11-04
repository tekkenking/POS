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
			$table->string('password');
			$table->string('image');
			$table->string('name');
			$table->string('gender');
			$table->string('phone');
			$table->string('email');
			$table->string('birthday');
			$table->string('houseaddress');
			$table->string('guarantor_image');
			$table->string('guarantor_name');
			$table->string('guarantor_gender');
			$table->string('guarantor_phone');
			$table->string('guarantor_email');
			$table->string('guarantor_address');
			$table->string('guarantor_workplace_phone');
			$table->string('guarantor_workplace_address');
			$table->integer('isloggedin');
			$table->string('loggedtime');
			$table->boolean('isenabled')->default(1);
			$table->timestamp('last_active')->default('0000-00-00 00:00:00');
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
		Schema::drop('users');
	}

}
