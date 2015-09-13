<?php

class RoleUserTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('role_user')->truncate();
	
		$list = [ 1 => 1 ];

		$roleuser =[];

		foreach($list as $userid=>$roleid){
			$roleuser[] = [ 
						'user_id' => $userid,  
						'role_id' => $roleid,  
						'created_at'=>sqldate(), 
						'updated_at'=>sqldate()
						];
		}

		// Uncomment the below to run the seeder
		 DB::table('role_user')->insert($roleuser);
		
	}

}
