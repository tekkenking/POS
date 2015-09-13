<?php

class SystemsettingsTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('systemsettings')->truncate();

		$systemsettings = array(
			'payment_mode'	=> 'Cash, Pos, Coupon',
			'dob_alert_day' => '5',
			'created_at' 	=> sqldate(),
			'updated_at' 	=> sqldate()
		);

		// Uncomment the below to run the seeder
		DB::table('systemsettings')->insert($systemsettings);
	}

}
