<?php

class AboutSoftwareController extends \BaseController{
	
	public function index(){
		$data['aboutsoftware'] = Coded::deCode();

		//Lets get the remaing Demo days if any
		$data['aboutsoftware']['demodays'] =  Coded::demoAlert($data['aboutsoftware']);

		return View::make('aboutsoftware', $data);
	}

	public function getactivatebox(){
		Larasset::start('footer')->js('charlimit', 'maskedinput');
		return View::make('error.gnu_activatebox');
	}

	public function postactivatebox(){
		$status = Coded::activateApp(Input::get('license_key'));

		if( $status ){
			$data['status'] = 'success';
			$data['message'] = 'Software activated';
		}else{
			$data['status'] = 'error';
			$data['message'] = 'License key is invalid';
		}

		return Response::json($data);
		//return Response::json(array('status'=>'success', 'message'=>'License key is incorrect'));
		//tt(Input::all());
	}

}