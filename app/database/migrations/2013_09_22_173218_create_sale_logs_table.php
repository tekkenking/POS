<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSaleLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sale_logs', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('mode_id');
			$table->integer('user_id');
			$table->integer('customer_id');
			$table->integer('receipt_id');
			$table->integer('product_id');
			$table->string('product');
			$table->string('brand');
			$table->string('productcat');
			$table->decimal('unitprice', 19,4)->default(0);
			$table->decimal('costprice', 19,4)->default(0);
			$table->integer('quantity');
			$table->string('discount', 10)->default(0);
			$table->decimal('total_unitprice', 19,4)->default(0);
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
		Schema::drop('sale_logs');
	}

}
