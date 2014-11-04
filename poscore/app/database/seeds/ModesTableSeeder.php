<?php

class ModesTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('modes')->truncate();

		$list = array(
				'retail' => 1,
				'wholesale' => 1,
				'distributor' => 1,
				'majordistributor' => 1,
					);

		$modes =array();
		foreach($list as $name=>$status){
			$modes[] = array('name'=>$name, 'status'=>$status, 'created_at'=>sqldate(), 'updated_at'=>sqldate());
		}

		// Uncomment the below to run the seeder
		DB::table('modes')->insert($modes);
	}

}
