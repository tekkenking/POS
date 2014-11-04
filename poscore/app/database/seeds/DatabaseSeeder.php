<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		//$this->call('MenusTableSeeder');
		/*$this->call('BrandsTableSeeder');
		$this->call('ProductcategoriesTableSeeder');
		$this->call('ProductsTableSeeder');
		$this->call('CustomersTableSeeder');
		$this->call('CustomersprofileTableSeeder');
		$this->call('BrandproductcategoryTableSeeder');
		$this->call('BrandproducttypeTableSeeder');*/
		//$this->call('ModesTableSeeder');
		//$this->call('SystemsettingsTableSeeder');
		$this->call('RolesTableSeeder');
		//$this->call('UsersTableSeeder');
	}

}