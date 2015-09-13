<?php

class BankRecordController extends \SecureBaseController
{

	public function index(){
		if(! Request::ajax() ){
			$data = $this->_viewentries();

			$this->layout->title = 'Bank records';
			$this->layout->content = View::make('bank.banktab', $data);
		}else{
			$method = Input::get('mode');
			$method = '_' . $method; // We prefix with the underscore(_) to indicate private method
			$data = $this->$method();
			return View::make('bank.bank'.$method, $data);
		}
	}

	private function _newentry(){
		Larasset::start('footer')->js('bootstrap-datepicker');
		Larasset::start('header')->css('bootstrap-datepicker');
		return array();
	}

	private function _viewentries(){
		//$type = Input::get('type', 'deposit_date');
		$daterange = Input::get('record_range', '');
		//Session::put('ss_select_banktype', $type);

		Larasset::start('header')->css('daterangepicker');
		Larasset::start('footer')->js('moment', 'daterangepicker');
		Larasset::start('footer')->js('dataTables-min', 'dataTables-bootstrap');

		$data['entries'] = Bankentry::with(array('user'=>function($q){
				$q->select('id', 'name');
		}));

		//Is the person Admin or not
		if( User::permitted( 'role.admin') !== true ){
			$data['entries'] = $data['entries']->where('user_id', Auth::user()->id);
		}
		
		if( $daterange !== '' && strpos($daterange, '-') !== FALSE ){
				$fromandto = explode('-', $daterange);
				$from = $fromandto[0];
				$to_plus_1day = (strtotime('now') - strtotime('yesterday')) + strtotime($fromandto[1]);
				$to = date('Y/n/d', $to_plus_1day );

				$data['from'] = date('Y/n/d', strtotime($fromandto[0]));
				$data['to'] = date('Y/n/d', strtotime($fromandto[1]));

		}else{
			$to = date('Y/n/d', strtotime('today'));
			$from = date('Y/n/d', mktime(0, 0, 0, date('n')-6, 1, date('y')));
			$data['from'] = $from;
			$data['to'] = $to;
		}

		$data['entries'] = $data['entries']->whereBetween('deposit_date', array(sqldate($from), sqldate($to)));
		$data['entries'] = $data['entries']->orderby('deposit_date', 'desc')->paginate(10);

		//tt($data);
		return $data;
	}

	public function create(){
		$all = Input::all();
		$all['user_id'] = Auth::user()->id;
		$all['deposit_date'] = sqldate($all['deposit_date']);
		$all['amount'] = unformat_money($all['amount']);
		$all['status'] = 0;

		//Insert into database
		$created = Bankentry::create($all);

		$data['status'] = 'success';
		$data['message'] = 'saved!';

		return Response::json($data);
	}
}