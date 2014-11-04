<?php

class ProductcategoriesTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('productcategories')->truncate();
		$list = array('Nail', 'Lips', 'Face', 'Eye', 'Others');

		$productcategories = array();
		foreach($list as $cat){
			$productcategories[] = array('name'=>$cat, 'created_at'=>sqldate(), 'updated_at'=>sqldate());
		}

		// Uncomment the below to run the seeder
		DB::table('productcategories')->insert($productcategories);
	}

}
