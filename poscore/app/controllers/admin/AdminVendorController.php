<?php

class AdminVendorController extends \AdminBaseController
{
	public function index(){
		if(! Request::ajax() ){
			$data = $this->_view();

			$this->layout->title = "Vendors - Create or View";
			$this->layout->content = View::make('admin.vendor.vendor_tab', $data);
		}else{
			$method = Input::get('mode');
			$method = '_' . $method; // We prefix with the underscore(_) to indicate private method
			$data = $this->$method();
			return View::make('admin.vendor.vendor'.$method, $data);
		}

	}

	private function _create(){
		//Larasset::start('footer')->js('bootstrap-datepicker');
		//Larasset::start('header')->css('bootstrap-datepicker');
		return array();
	}

	private function _view(){
		//$type = Input::get('type', 'created_at');
		//$daterange = Input::get('record_range', '');
		//Session::put('ss_select_expen', $type);

		//Larasset::start('header')->css('daterangepicker');
		//Larasset::start('footer')->js('moment', 'daterangepicker');
		Larasset::start('footer')->js('dataTables-min', 'dataTables-bootstrap');

		$data['entries'] = DB::table('vendors')->orderby('id', 'desc')->get();

		return $data;
	}

	public function edit(){
		//Larasset::start('footer')->js('bootstrap-datepicker');
		//Larasset::start('header')->css('bootstrap-datepicker');
		
		$all = Input::all();
		$id = $all['id'];
		
		//Lets fetch the updated data and return it through ajax
		$data['row'] = Vendor::where('id', $id)->get()->toArray()[0];

		
		return View::make('admin.vendor.vendor_edit', $data);
	}

	public function saveEdit(){
		$r = Input::all();
		$id = $r['id'];

		unset($r['id']);
		//We update the database
		Vendor::find($id)->update($r);

		//Lets fetch the updated data and return it through ajax
		$r['id'] = $id;
		
		$data['status'] = 'success';
		$data['message'] = $r;

		return Response::json($data);
	}


	public function save(){
		$r = Input::all();
		//$r['date'] = sqldate($r['date']);
		//$r['amount'] = unformat_money($r['amount']);
		$r['user_id'] = Auth::user()->id;
		//$r['status'] = (User::permitted( 'role.admin') === true) ? 1 : 0;

		//Insert into database
		$created = Vendor::create($r);

		$data['status'] = 'success';
		$data['message'] = 'Record saved successfully!';
		return Response::json($data);
	}

	public function delete(){
		$ids = explode(',', Input::get('javascriptArrayString'));
		Vendor::destroy($ids);
		$data['status'] = 'success';
		$data['message'] = count($ids) . ' Row(s) was deleted successfully';
		return Response::json($data);
	}

}