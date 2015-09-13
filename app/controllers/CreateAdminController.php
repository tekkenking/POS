<?php

class CreateAdminController extends \BaseController
{
	public function index(){

		if( Session::get('firstuserurl') === Input::get('firstuserurl') ){
			$this->layout->title='Create Admin';
			$this->layout->content = View::make('firstuser.create');
		}else{
			return Redirect::route('home');
		}
	}

	public function createFirstAdmin(){
		$user = new User;

		$user->name = Input::get('name');
		$user->password = Hash::make(Input::get('password'));
		$user->usertoken = User::generateUsertoken();
		$user->username = User::generateUsername($user->name);
		$user->phone = Input::get('phone');
		$user->email = Input::get('email');
		$user->gender = Input::get('gender');

		User::$rulex = 'user - gender houseaddress';
				if(!$user->save()){
					$data = $user->validatorStatus;
				}else{
					//Lets set a default role to the new user
					User::assignRole($user->id, Config::get('role.admin'));

					//Release the url unique ID
					Session::forget('firstuserurl');

					$data['status'] = 'success';
					//$data['url'] = URL::route('login');
					$data['message'] = "User created successfully <h5 class='bolder red'> Username: ". $user->username ."</h5> <h5 class='bolder red'> Password: ". Input::get('password') ."</h5> <a href='". URL::route('login') ."' class='btn btn-block btn-inverse'>Go to login page</a>";
				}
		return Response::json($data);
	}
}