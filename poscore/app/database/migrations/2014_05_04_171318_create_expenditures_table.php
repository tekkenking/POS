<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateExpendituresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('expenditures', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->string('item_name');
			$table->string('payment_type');
			$table->string('amount');
			$table->string('date');
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
		Schema::drop('expenditures');
	}

}
