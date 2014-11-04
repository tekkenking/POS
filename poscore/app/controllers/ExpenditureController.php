<?php

class ExpenditureController extends \SecureBaseController
{
	public function index(){
		if(! Request::ajax() ){
			$data = $this->_view();

			$this->layout->title = "Expenditure - Create or View";
			$this->layout->content = View::make('expenditures.expen_index', $data);

		}else{
			$method = Input::get('mode');
			$method = '_' . $method; // We prefix with the underscore(_) to indicate private method
			$data = $this->$method();
			return View::make('expenditures.expen'.$method, $data);

		}

	}

	private function _create(){
		Larasset::start('footer')->js('bootstrap-datepicker');
		Larasset::start('header')->css('bootstrap-datepicker');
		return array();
	}

	private function _view(){
		$daterange = Input::get('record_range', '');
		//Session::put('ss_select_expen', $type);

		Larasset::start('header')->css('daterangepicker');
		Larasset::start('footer')->js('moment', 'daterangepicker');
		Larasset::start('footer')->js('dataTables-min', 'dataTables-bootstrap');

		$data['entries'] = Expenditure::with(array('user'=>function($q){
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

				$data['from'] = format_date2($fromandto[0]);
				$data['to'] = format_date2($fromandto[1]);
		}else{
				$to = date('Y/n/d', strtotime('today'));
				$from = date('Y/n/d', strtotime('first day of this month'));

				$data['to'] = format_date2($to);
				$data['from'] =format_date2($from);
		}

		$data['entries'] = $data['entries']->whereBetween('date', array(sqldate($from), sqldate($to)));
		//$data['entries'] = $data['entries']->orderby('date', 'desc')->paginate(10);

		$data['entries'] = $data['entries']->orderby('date', 'desc')->get();

		//tt($data);
		return $data;
	}

	public function save(){
		$r = Input::all();
		$r['date'] = sqldate($r['date']);
		$r['amount'] = unformat_money($r['amount']);
		$r['user_id'] = Auth::user()->id;
		$r['status'] = (User::permitted( 'role.admin') === true) ? 1 : 0;

		//Insert into database
		$created = Expenditure::create($r);

		$data['status'] = 'success';
		$data['message'] = 'Record saved successfully!';
		return Response::json($data);
	}

}