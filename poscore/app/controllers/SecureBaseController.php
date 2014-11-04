<?php

class SecureBaseController extends \BaseController{

	public function __construct(){
		parent::__construct();

		//We have to rap this filter into Ajax check conditional statement
		//So that we can tell ajax if our users session is expired or not.
		if( !Request::ajax() ){
			$this->beforeFilter('auth');
		}

		if( Auth::check() && Auth::user()->isloggedin !== 1 ){
			$this->logout();
		}

		//tt(User::permitted(1, Auth::user()->id));

		//Roles Filter
		//$this->beforeFilter('role');

		//tt($this->sortUsersRole());
	}

	/*public function sortUsersRole(){
		return User::getOnlyUserRoleID(2);
		//foreach( $rolesArray as $roles ){

			//}
	}*/
}