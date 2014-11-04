<?php

Class AdminPasswordRecoveryController extends \NonSecureBaseController{


	public function index(){
		if( Session::get('fpwd_timestamp', null) === null ){
			tt('No direct access.. Go back');
		}

		//$userinfo = Session::get('fpwd_userinfo');
		//tt($userinfo);
		$data['user'] = Session::get('fpwd_userinfo');
		$this->layout->title = 'Enter new password';
		$this->layout->content = View::make('admin.recoverpassword', $data);
	}

	public function recover(){
		$pwd = Input::get('password');
		$cpwd = Input::get('confirm_password');

		if( $pwd !== $cpwd ){
			$data['status'] = 'danger';
			$data['message'] = 'Password mis-match';
			return Response::json($data);
		}

		$userinfo = Session::get('fpwd_userinfo');

		$userinfo->password = Hash::make($pwd);
		if( $userinfo->save() ){
			Session::forget('fpwd_userinfo');
			Session::forget('fpwd_timestamp');

			$data['status'] = 'success';
			$data['message'] = 'New password created successfully. <br/> Redirecting you to login';
			$data['url'] = URL::route('home');
		}else{
			$data['status'] = 'danger';
			$data['message'] = 'New password creation failed!!';
		}

		return Response::json($data);
	}

	public function logoutrecover(){
		if( Session::get('fpwd_timestamp', null) === null ){
			tt('No direct access.. Go back');
		}

		Session::forget('fpwd_userinfo');
		Session::forget('fpwd_timestamp');

		return Redirect::route('home');
	}
}