<?php

class UsersTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('users')->truncate();
	
		$list = array(
				'debola1' => 1,
				'fresh2' => 2,
				'melee3' => 3,
					);

		$users =array();
		foreach($list as $username=>$role){
			$users[] = array('username'=>$username, 'usertoken'=>Makehash::random('number',6), 'password'=>Hash::make('demo'),  'created_at'=>sqldate(), 'updated_at'=>sqldate());
		}

		// Uncomment the below to run the seeder
		 DB::table('users')->insert($users);
		
	}

}
