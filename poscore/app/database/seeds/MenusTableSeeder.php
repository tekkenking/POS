<?php

class MenusTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('menus')->truncate();

		$list = array(
				'Dashboard' => 'admindashboard',
				'Stock' => 'adminstock',
					);

		$menus =array();
		foreach($list as $name=>$urlname){
			$menus[] = array('name'=>$name, 'urlname'=>$urlname, 'created_at'=>sqldate(), 'updated_at'=>sqldate());
		}

		// Uncomment the below to run the seeder
		DB::table('menus')->insert($menus);
	}

}
