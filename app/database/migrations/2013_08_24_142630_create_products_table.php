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
			$table->string('barcodeid', 50)->nullable();
			$table->string('name');
			$table->integer('quantity')->default(0);
			$table->integer('almost_finished')->default(0);
			$table->decimal('costprice')->default(0);
			//$table->decimal('totalcostprice')->default(0);
			$table->decimal('retail_price', 19,4)->default(0);
			$table->string('retail_discount', 10)->default(0);
			$table->decimal('retail_discountedprice', 19,4)->default(0);
			$table->decimal('retail_totalprice', 19,4)->default(0);
			$table->decimal('wholesale_price', 19,4)->default(0);
			$table->string('wholesale_discount', 10)->default(0);
			$table->decimal('wholesale_discountedprice', 19,4)->default(0);
			$table->decimal('wholesale_totalprice', 19,4)->default(0);
			$table->decimal('distributor_price', 19,4)->default(0);
			$table->string('distributor_discount', 10)->default(0);
			$table->decimal('distributor_discountedprice', 19,4)->default(0);
			$table->decimal('distributor_totalprice', 19,4)->default(0);
			$table->decimal('majordistributor_price', 19,4)->default(0);
			$table->string('majordistributor_discount', 10)->default(0);
			$table->decimal('majordistributor_discountedprice', 19,4)->default(0);
			$table->decimal('majordistributor_totalprice', 19,4)->default(0);
			//$table->string('vat');
			$table->boolean('published')->default(0);
			$table->softDeletes();
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
		Schema::drop('products');
	}

}
