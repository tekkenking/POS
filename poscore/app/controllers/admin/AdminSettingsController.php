<?php

Class AdminSettingsController extends \AdminBaseController
{

	public function getSystemsettings(){
		if( Request::ajax() ){
			$mode = Input::get('mode', 'general');
			return View::make('admin.systemsettings.'.$mode);
		}

		$this->layout->title = 'Admin system settings';
		$this->layout->content = View::make('admin.systemsettings.tab');
	}

	public function previewreceipt(){
		return View::make('admin.systemsettings.demo_receipt_preview');
	}

	public function postGeneral(){
		return Response::json($this->_returnResponse($this->_save(Input::all())));
	}	

	/*public function postTransaction(){
		return Response::json($this->_returnResponse($this->_save(Input::all())));
	}*/

	public function postReceipt(){
		$all = Input::all();

		$all['show_salesperson'] = (isset($all['show_salesperson']) && $all['show_salesperson'] === 'on') ? 1 : 0;

		$all['show_customerdetails'] = (isset($all['show_customerdetails']) && $all['show_customerdetails'] === 'on') ? 1 : 0;

		$all['show_paymentmode'] = (isset($all['show_paymentmode']) && $all['show_paymentmode'] === 'on') ? 1 : 0;

		return Response::json($this->_returnResponse($this->_save($all)));
	}	

	/*public function postRegional(){
		return Response::json($this->_returnResponse($this->_save(Input::all())));
	}*/

	private function _returnResponse($status){
		if( ! $status ){
			$data['status'] = 'danger';
			$data['message'] = 'Error in updating system settings';
		}else{
			$data['status'] = 'success';
			$data['message'] = 'Update successfully';
		}

		return $data;
	}

	private function _save($data){
		$all = $this->_filterDone($data);
		$status = Systemsetting::find(1)->update($all);
		return $status;
	}

	private function _filterDone($data){
		return array_map('trim', $data);
	}
}