<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRecordMissingproductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('record_missingproducts', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('brand_id');
			$table->integer('mode_id');
			$table->integer('productcat_id');
			$table->integer('product_id');
			$table->string('name');
			$table->integer('quantity_removed');
			$table->string('total_lostprice');
			$table->integer('quantity_remaining');
			$table->string('total_remainingprice');
			$table->string('total_discountprice');
			$table->string('admin_name');
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
		Schema::drop('record_missingproducts');
	}

}
