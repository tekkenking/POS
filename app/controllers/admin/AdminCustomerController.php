<?php

class AdminCustomerController extends \AdminBaseController{

	public function adminShowCustomerForm(){
		Larasset::start('header')->css('bootstrap-datepicker');
		Larasset::start('footer')->js('bootstrap-datepicker');

		$data['modes'] =  Mode::listModes();
		return View::make('customerform', $data);
	}

	public function adminListCustomers(){
		Larasset::start('footer')->js('dataTables-min', 'dataTables-bootstrap');

		$allcustomers = Customer::with(
			array('customerlog')
		)->get();

		$data['customers'] = $allcustomers->toArray();

		if(Request::ajax()){
			return View::make('admin.listcustomers', $data);
		}else{
			$this->layout->title = 'Create, Edit, Delete Staff';
			$this->layout->content = View::make('admin.tab_customermenus', $data);
		}
	}

	public function adminDeleteCustomers(){
		$id = Input::get('javascriptArrayString');

		//if( Customer::find($id)->customerlog )
		$hasRecord = Customer::find($id)->customerlog()->lists('id');

		Customer::destroy($id);

		$data['status'] = 'success';
		$data['message'] = 'Customer\'s record deleted successfully';
		return Response::json($data);
	}

	public function adminPreviewCustomerProfile($customerID){
		Larasset::start('footer')->js('bootstrap_editable', 'bootstrap-datepicker', 'select2');
		Larasset::start('header')->css('bootstrap_editable', 'bootstrap-datepicker', 'select2');

		//Lets the details of a customer
		$customer = Customer::with(
				array(
						'customerlog', 
						'salelog'=>function($query){
								  $query->take(10)
										->orderBy('id', 'desc');
									}

					 )

		)->find($customerID);

		$customer= $customer->toArray();

		//Lets get the customer's Activity
		$activity = $customer['salelog'];
		unset($customer['salelog']);

		//Lets get how much the customer's ever bought
		$customerlog = $customer['customerlog'];
		unset($customer['customerlog']);

		$data['customerlog'] = $customerlog;
		$data['customer'] = $customer;
		//$data['activities'] = $activity;

		return View::make('admin.previewcustomerprofile', $data);
	}

	public function update(){
		$inputs = Input::all();

		$customer = Customer::find($inputs['pk']);

		$customer->$inputs['name'] = $inputs['value'];

		//tt($customer->save());

		if($customer->save()){
			$data['status'] = 'success';
		}else{
			$data['status'] = 'danger';
		}

		
		return Response::json($data);
	}

	/*public function adminRegisterNewCustomer(){
		$customer = new Customer;
		$customer->name = $name = Input::get('name');
		$customer->phone = Input::get('phone');
		$customer->email = Input::get('email');
		$customer->birthday = db_dob_date_format(Input::get('birthday'));
		$customer->mode_id = (int)Input::get('customer_type');
		$customer->token = $token = Makehash::random('number',6);
		$customer->createdby = Auth::user()->username;
			
			if(!$customer->save()){
				$data = $customer->validatorStatus;
			}else{
				$message = "
					<p>Registration successful!</p>
					<p>" . $name . " token is: <span class='label label-large label-info new-token'>". $token ."</span>
					</p>
					
				";
				$data['status'] = 'success';
				$data['message'] = $message;
				$data['detail'] = "Name: {$name} | Token-ID: {$token}";
			}

		return Response::json($data);
	}*/

	public function getHistory($id, $when=''){
		return $this->_processHistory($id, $when);
	}

	public function searchHistory(){
		//tt(Input::all());

		$daterange = Input::get('historyrange');
		$customerID = Input::get('customer_id');

		return $this->_processHistory($customerID, $daterange);
	}

	private function _processHistory($id, $daterange){
		Larasset::start('header')->css('daterangepicker');
		Larasset::start('footer')->js('moment', 'daterangepicker');

		if( strpos($daterange, '-') !== FALSE ){
				$fromandto = explode('-', $daterange);
				$from = sqldate($fromandto[0]);
				$to_plus_1day = (strtotime('now') - strtotime('yesterday')) + strtotime($fromandto[1]);
				$to = sqldate(date('Y/n/d', $to_plus_1day ));
				$data['todate'] = $fromandto[1];
			}else{
				$from = sqldate(date('Y/n/d', strtotime('first day of this month')));
				$to = sqldate('+1day');
				$data['todate'] = $to;
			}

			

		$customerlog = Salelog::with(array('product'=>function($q){
				$q->select('id','name', 'productcat_id')
				->with(array('categories'=>function($qr){
					$qr->select('id','type');
				}));
		}))->where('customer_id', '=', $id)
		   ->whereBetween('created_at', array($from, $to))
		   ->orderBy('id', 'desc');

		//tt(groupThem($customerlog->get()->toArray(), 'receipt_id'));

		$customerDetails = Customer::where('id','=',$id)->first();

		//$data['customerhistory'] = $customerlog->get()->toArray();
		$data['customerhistory'] = groupThem($customerlog->get()->toArray(), 'receipt_id');
		$data['customerdetail'] = $customerDetails;
		$data['fromdate'] = $from;
		$this->layout->title = 'Customer History';
		$this->layout->content = View::make('admin.customerhistory', $data);

	}

	public function extractor($field="email"){
		$data['extracted'] = Customer::lists($field);

		$data['type'] = $field;
		$data['count'] = count($data['extracted']);
		$data['extracted'] = implode(', ',$data['extracted']);
		
		return View::make('admin.listcustomers_extractor', $data);
	}
}