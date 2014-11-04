<?php

class RolesTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('roles')->truncate();

		$rolex = array(
			'1'=>'admin',
			'2'=>'stock manager',
			'3'=>'sales manager',
			'4'=>'sales'
		);

		$roles = array();
		foreach( $rolex as $name ){
			$roles[] = array('name'=>$name);
		}

		// Uncomment the below to run the seeder
		DB::table('roles')->insert($roles);


		DB::table('role_user')->truncate();
		/*array(
			arrayIndex => array(user_id => role_id)
		)*/
		$role = array(
				1 => array(1=>1),
				2 => array(2=>2),
				3 => array(2=>3),
				4 => array(3=>4)
			);
		$role_user = array();
		foreach($role as $v){
			foreach( $v as $k=>$r ){
				$role_user[] = array('user_id'=>$k, 'role_id'=>$r);
			}
		}

		DB::table('role_user')->insert($role_user);
	}

}
