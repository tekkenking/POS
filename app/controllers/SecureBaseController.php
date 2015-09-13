<?php

class SecureBaseController extends \BaseController{

	public function __construct(){
		//tt(Auth::user(), false, 'AM here');

		//parent::__construct();

		

		//We have to rap this filter into Ajax check conditional statement
		//So that we can tell ajax if our users session is expired or not.
		if( ! Request::ajax() ){
			$this->beforeFilter('auth');
		}

		//tt(Auth::user()->isloggedin);

		if( Auth::check() && Auth::user()->isloggedin === 0 ){
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