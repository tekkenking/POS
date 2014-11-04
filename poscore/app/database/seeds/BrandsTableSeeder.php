<?php

class BrandsTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('brands')->truncate();

		$list = array(
				'flp' => 'Forever living product',
				'sleek' => 'Sleek makeup',
				'glamour56' => 'Glamour56 makeup',
				'iman' => 'Iman makeup',
				'marykay' => 'MaryKay makeup',
				'mac' => 'Mac makeup'
					);

		$brands = array();
		foreach($list as $name=>$caption){
			$brands[] = array('name'=>$name, 'caption'=>$caption, 'created_at'=>sqldate(), 'updated_at'=>sqldate());
		}


		// Uncomment the below to run the seeder
		DB::table('brands')->insert($brands);
	}

}
