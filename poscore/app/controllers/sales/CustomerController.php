<?php

class CustomerController extends \SalesBaseController{
	
	public function modal_showCustomerForm()
	{
		Larasset::start('header')->css('bootstrap-datepicker');
		Larasset::start('footer')->js('bootstrap-datepicker');
		return View::make('customerform');
	}

	public function show_searchCustomerToken()
	{
		return View::make('show_searchCustomerToken');
	}

	public function searchCustomerToken(){
		$str = strtolower(Input::get('gettoken'));

		//$field = ( preg_match("/^[0-9 ]*$/", $str) !== 0 ) ? 'phone' : 'email';

		//We check to determine if we search through phone or token
		//if( $field === 'phone' ){
			$field = (strlen( $str ) < 11 ) ? 'token' : 'phone';
		//}

		$tokenInfo = Customer::where( $field, '=', Input::get('gettoken') )
				->select('id', 'name', 'token')
				->get()
				->first();

		if( !empty($tokenInfo) ){
			$data['status'] = 'success';
			$data['message'] = $tokenInfo->name . ' Token-ID found!';
			$data['detail'] = "Name: " . $tokenInfo->name . " | Token-ID: " . $tokenInfo->token . "";
		}else{
			$data['status'] = 'error';
			$data['message'] = 'Customer does not exist';
		}
		return Response::json($data);
	}

	public function adminShowCustomerForm(){
		Larasset::start('header')->css('bootstrap-datepicker');
		Larasset::start('footer')->js('bootstrap-datepicker');

		return View::make('customerform');
	}

	public function adminListCustomers(){
		Larasset::start('footer')->js('dataTables-min', 'dataTables-bootstrap');

		$allcustomers = Customer::all();
		$data['customers'] = $allcustomers->toArray();

		if(Request::ajax()){
			return View::make('admin.listcustomers', $data);
		}else{
			$this->layout->title = 'Create, Edit, Delete Staff';
			$this->layout->content = View::make('admin.tab_customermenus', $data);
		}
	}

	public function registerNewCustomer(){

		$customer = new Customer;
		$customer->name = $name = Input::get('name');
		$customer->phone = Input::get('phone');
		$customer->email = Input::get('email');
		$customer->birthday = sqldate(Input::get('birthday'));
		$customer->mode_id = (int)Input::get('customer_type');
		$customer->token = $token = Makehash::random('number',6);
		$customer->createdby = Auth::user()->username;
			
			if(!$customer->save()){
				$data = $customer->validatorStatus;
			}else{
				$message = "<p>Registration successful!</p>
					<p>" . $name . " token is: <span class='label label-large label-info new-token'>". $token ."</span>
					</p>";
				$data['status'] = 'success';
				$data['message'] = $message;
				$data['detail'] = "Name: {$name} | Token-ID: {$token}";
			}

		return Response::json($data);
	}
}