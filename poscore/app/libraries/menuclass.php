<?php

Class MenuClass
{
	//private static $menu = 

	private static function _prepareMenu(){
		return array(
					'dashboard' 	=> array(
							'name'		=> 'Dashboard',
							'urlname' 	=> 'admindashboard', 
							'role' 		=> 'role.admin'
										),
					'stock manager' => array(
							'name'		=> 'Stock',
							'urlname'	=> 'adminstock', 	
							'role'		=> 'role.stock manager'
										),
					'staff/customer'=> array(
							'name'		=> 'Staff / Customer',
							'urlname'	=> 'adminstaffsactivityfeeds',
							'role'		=> 'role.admin'
										),
					'history records'=> array(
							'name'		=> 'Inventory records',
							'urlname'	=> 'adminHomeRecords',
							'role'		=> 'role.admin'
										),					
					'bank records'=> array(
							'name'		=> 'Bank entries',
							'urlname'	=> 'entries',
							'role'		=> 'role.admin'
										),
					'expenditures'=> array(
							'name'		=> 'Expenditures',
							'urlname'	=> 'expenditures',
							'role'		=> 'role.admin'
										),
					'vendors'=> array(
							'name'		=> 'Vendors',
							'urlname'	=> 'vendors',
							'role'		=> 'role.admin'
										),
					'cart'		  => array(
							'name'		=> 'Cart',
							'urlname'	=> 'home',
							'role'		=> 'role.sales'
										),
					'systemsettings' => array(
							'name'		=> 'System settings',
							'urlname'	=> 'systemsettings',
							'role'		=> 'role.admin'
										),
					'stock update record' => array(
							'name'		=>	'Stock update record',
							'urlname'	=>	'stockupdate.record',
							'role'		=>	'role.stock manager'
						)
					);
	}

	public static function getMenu($menukey){
		$str = explode('.', $menukey);
		$key1 = strtolower($str[0]);
		$key2 = strtolower($str[1]);

		$menu = static::_prepareMenu();
		return $menu[$key1][$key2];
	}

	//Return array
	public static function getMenuArray($key){
		$key = strtolower($key);
		$menu = static::_prepareMenu();
		return $menu[$key];
	}
}