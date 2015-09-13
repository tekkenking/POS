<?php

class AdminUserController extends \AdminBaseController
{
	public function index(){
		$this->layout->title = 'Users settings';
		$this->layout->content = View::make('admin.user');
	}

	public function showUserLoggedActivities(){
		$activities = Usersactivity::where('activity_type','=','loggedin')
						->orWhere('activity_type','=','loggedout')
						->take(20)
						->orderBy('id', 'desc')
						->get();
		$data = array();

		if( $activities != null ){
			$data['activities'] = $activities;
		}

		if( Request::ajax() ){
			return View::make('activity_layouts.activities_feed_2columns', $data);
		}else{
			$this->layout->title = 'Customers & Staffs Area';
			$this->layout->content = View::make('activity_layouts.tab_users_activities_feeds', $data);
		}
	}

	public function showUserSalesActivities(){
		$activities = Usersactivity::where('activity_type','=','sale')
						->take(20)
						->orderBy('id', 'desc')
						->get();
		$data = array();

		if( $activities != null ){
			$data['activities'] = $activities;
		}

		//tt($data['activities']->toArray());

		return View::make('activity_layouts.activities_feed_2columns', $data);
	}

	public function adminShowStaffForm(){
		Larasset::start('header')->css('bootstrap-datepicker');
		Larasset::start('footer')->js('bootstrap-datepicker');

		return View::make('admin.staffform');
	}

	public function adminListStaffs(){
		Larasset::start('footer')->js('dataTables-min', 'dataTables-bootstrap');

		$allstaffs = User::all();
		$data['staffs'] = $allstaffs->toArray();

		if(Request::ajax()){
			return View::make('admin.liststaffs', $data);
		}else{
			$this->layout->title = 'Create, Edit, Delete Staff';
			$this->layout->content = View::make('admin.tab_staffmenus', $data);
		}
	}

	public function adminPreviewStaffProfile($staffID){
		Larasset::start('footer')->js('bootstrap_editable', 'bootstrap-datepicker');
		Larasset::start('header')->css('bootstrap_editable', 'bootstrap-datepicker');

		//Lets the details of a staff
		$staff = User::find($staffID);

		//Lets get the staff's Activity
		$activity = Usersactivity::where('user_id', '=', $staffID)
		->take(10)
		->orderBy('id', 'desc')
		->get();

		//tt($activity->toArray());

		//Lets get how much the staff's ever sold
		$salesworth = User::find($staffID)->salelogs()->sum('total_unitprice');

		$data['salesworth'] = 0.00;
		$data['role'] = Role::all()->lists('id', 'name');

		//tt($data['role']);
		
		if( !empty($salesworth) && $salesworth !== null){
			$data['salesworth'] = $salesworth ;
		}

		$data['staff'] = $staff->toArray();
		$data['activities'] = $activity;

		return View::make('admin.previewstaffprofile', $data);
	}

	public function staffUpdate(){
		$inputs = Input::all();
		//tt($inputs);
		$staff = User::find($inputs['pk']);

		//If the value key is not set..
		//We'll return from this point
		//To avoid showing program error
		if( !isset($inputs['value'])  ){
			$data['status'] = 'error';
			return Response::json($data);
		}

		$staff->$inputs['name'] = $inputs['value'];

		//Roles
		if( $inputs['name'] === 'role' ){
			$staff->roles()->detach();
			$staff->roles()->attach($inputs['value']);
			$data['status'] = 'success';
			return Response::json($data);
			
		}


		//Fullname
		if( $inputs['name'] === 'name' ){
			$fullname = strtolower($inputs['value']);
			$username = explode(' ', $fullname);
			$staff->$fullname;
			$staff->username = $username[0] . $inputs['pk'];
			//$staff->save();

			$data['fullname'] = $inputs['value'];
			$data['username'] = $username[0] . $inputs['pk'];
		}

		//Password
		if( $inputs['name'] === 'password' ){
			$staff->password = Hash::make($inputs['value']);
		}

		$staff->save();
		$data['status'] = 'success';
		return Response::json($data);
	}

	//Customers Registration is already in customerController.php
	public function staffRegistration(){
		//tt(Input::all());
		$user = new User;
		$user->name 		= strtolower(Input::get('name'));
		$user->birthday 	= sqldate(Input::get('birthday'));
		$user->gender 		= Input::get('gender');
		$user->username 	= User::generateUsername($user->name);
		$user->password 	= $user->setNewDefaultPassword();
		$user->usertoken 	= User::generateUsertoken();
		$user->phone 		= Input::get('phone');
		$user->email 		= Input::get('email');
		$user->houseaddress 		= Input::get('houseaddress');
		$user->guarantor_name 				= Input::get('guarantor_name');
		$user->guarantor_gender 			= Input::get('guarantor_gender');
		$user->guarantor_phone 				= Input::get('guarantor_phone');
		$user->guarantor_email 				= Input::get('guarantor_email');
		$user->guarantor_address 			= Input::get('guarantor_houseaddress');
		$user->guarantor_workplace_phone 	= Input::get('guarantor_workplacephone');
		$user->guarantor_workplace_address 	= Input::get('guarantor_workplaceaddress');

		User::$rulex = 'user';
		if(!$user->save()){
			$data = $user->validatorStatus;
		}else{
			//Lets set a default role to the new user
			User::assignRole($user->id);

			$data['status'] = 'success';
			$data['message'] = 'User created successfully';
		}

		return Response::json($data);
	}
}