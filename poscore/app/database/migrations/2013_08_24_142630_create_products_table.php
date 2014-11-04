<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('productcat_id');
			$table->integer('brand_id');
			$table->string('barcodeid', 50);
			$table->string('name');
			$table->integer('quantity');
			$table->integer('almost_finished')->default(0);
			$table->string('costprice')->default(0);
			//$table->string('totalcostprice')->default(0);
			$table->string('retail_price');
			$table->integer('retail_discount');
			$table->string('retail_discountedprice');
			$table->string('retail_totalprice');
			$table->string('wholesale_price');
			$table->integer('wholesale_discount');
			$table->string('wholesale_discountedprice');
			$table->string('wholesale_totalprice');
			$table->string('distributor_price');
			$table->integer('distributor_discount');
			$table->string('distributor_discountedprice');
			$table->string('distributor_totalprice');
			$table->string('majordistributor_price');
			$table->integer('majordistributor_discount');
			$table->string('majordistributor_discountedprice');
			$table->string('majordistributor_totalprice');
			//$table->string('vat');
			$table->boolean('published')->default(0);
			$table->softDeletes();
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
		Schema::drop('products');
	}

}
