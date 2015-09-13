<?php

class StaffController extends \SecureBaseController {

	public function index(){
		$this->layout->title = 'Change your password';
		$this->layout->content = View::make('changepassword');
	}

	public function changePassword(){
		$oldPassword = Input::get('oldpassword');
		$newPassword = Input::get('newpassword');
		$confirm_newPassword = Input::get('confirm_newpassword');

		$data['status'] = 'error';
		if (Hash::check($oldPassword, Auth::user()->password) )
		{
			if( $newPassword === $confirm_newPassword ){
				$user = User::find(Auth::user()->id);
				$user->password = Hash::make($newPassword);
				$user->save();
				$data['status'] = 'success';
				$data['message'] = 'Password change successfully';
			}else{
				$data['message'] = 'New password and Confirm password must match';
			}

		}else{
				$data['message'] = 'Old password is incorrect';
		}

		return Response::json($data);
	}
}