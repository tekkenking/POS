<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStockLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stock_logs', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('product_id');
			$table->integer('mode_id');
			$table->string('user_name');
			$table->string('brand_name');
			$table->string('productcategory_name');
			$table->string('product_name');
			$table->integer('current_quantity')->nullable();
			$table->string('added_quantity')->nullable();
			$table->integer('updated_quantity');
			$table->string('costprice')->default(0);
			$table->string('unitprice');
			$table->string('unitprice_discounted');
			$table->integer('discount');
			$table->string('total_price');
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
		Schema::drop('stock_logs');
	}

}
