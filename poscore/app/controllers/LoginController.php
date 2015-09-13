<?php

class LoginController extends \NonSecureBaseController {
	private $adminLogin = false;

	/**
	 * Display the default page
	 *
	 * @return Response
	 */
	public function index()
	{
		Larasset::start('footer')->js('vegas');
		Larasset::start('header')->css('vegas');

		$this->layout->title = Config::get('software.karanamkiahe') . ' Login';
		$this->layout->content = View::make('login');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function auth()
	{
		$username = strtolower(Input::get('username'));
		$password = Input::get('password');

		$user = array(
			'username' => $username,
			'password' => $password
		);

		//This would redirect you to forget password if true
		if($this->_isForgetPassword($username)){
			$fpwd_stat = $this->_gotoForgetPassword($username, $password);
			return Response::json($fpwd_stat);
		}

		Xvalidate::filter('login');
		$validation = Xvalidate::these($user);

		if( $validation->passes() === FALSE ){
			//tt($validation->messages());
			 return Response::json($validation->messages());
		}

		if( $this->firstUser() === TRUE ){
			$ds = md5(strtotime('now'));
			Session::put('tempUrl.firstUser', $ds);

			$data['url'] = URL::route('createAdmin.firstUserForm') . '?firstuserurl=' . $ds;
			$data['message'] = '<b>NOTE:</b> First time login attempt, would redirect you to create the software ADMINISTRATOR';
			$data['status'] = 'info';

			return Response::json($data);
		}

		if( $this->AuthLogin($user) === FALSE ){

			tt('danger not');

			$data['status'] = 'danger';
			$data['message'] = 'User does not exists..';
			return Response::json($data);
		}
		
		if(Auth::user()->isenabled != 1){
			$this->logout();
			$data['status'] = 'danger';
			$data['message'] = 'Account is disabled. Contact administrator';
			return Response::json($data);
		}

		//Log the login activity
		$this->saveLoginActivity();

		//Update Logged in time
		$this->updateLoggedInfo();

		$this->updateisLoggedin(1);
		
		$data['status'] = 'success';
		$data['message'] = 'Login successful. Redirecting...';
		$data['url'] = URL::route($this->_loginRoleURL());
		return Response::json($data);
	}

	private function _isForgetPassword($username){
		$check = preg_match("/forgetpassword/", $username);
		return $check;
	}

	private function _gotoForgetPassword($forgetPasswordusername, $password){
		$usernameArray = array_filter(preg_split("/forgetpassword/", $forgetPasswordusername));
		$username = array_shift($usernameArray);
		
		$adminInfo = User::where('username', $username)->first();

		if( $adminInfo === NULL ){ 
			$data['status'] = 'danger'; 
			$data['message'] = 'User does not exist';
			return $data;
		}
		
		if( date('h:i a', strtotime('now')) === date('h:i a', strtotime($password)) ){
			$data['status'] = 'success';
			$data['message'] = "User confirmed. Redirecting to change password";

			//To secure the admin forget password page
			Session::put('fpwd_timestamp', strtotime('now'));
			Session::put('fpwd_userinfo', $adminInfo);

			$data['url'] = URL::route('adminForgotPassword');
		}else{
			$data['status'] = 'danger';
			$data['message'] = "Unknown user.";
		}

		return $data;
	}

	private function firstUser(){
		return (User::all()->first() === NULL) ? TRUE : FALSE;
	}

	private function AuthLogin(Array $user){
		return $this->userAuth($user);
	}

	private function userAuth($user){
		return Auth::attempt($user);
	}

	private function saveLoginActivity(){
		//Log the login activity
		doUserActivity::saveActivity('loggedin');
	}

	private function updateLoggedInfo(){
		doUserActivity::saveActivity('updateloggedTime', 1);
	}

	private function _loginRoleURL(){
		$user = Auth::user();

		//For Admin
		if( User::permitted( 'role.admin') ){ return 'admindashboard'; }

		//For stockmanager
		if( User::permitted( 'role.stock manager') ){ return 'adminstock'; }

		//For sales
		if( User::permitted( 'role.sales') ){ return 'home'; }

	}

}