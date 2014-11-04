<?php

class AdminExpenditureController extends \AdminBaseController
{
	public function index(){
		if(! Request::ajax() ){
			$data = $this->_view();

			$this->layout->title = "Expenditure - Create or View";
			$this->layout->content = View::make('admin.expenditures.expen_index', $data);

		}else{
			$method = Input::get('mode');
			$method = '_' . $method; // We prefix with the underscore(_) to indicate private method
			$data = $this->$method();
			return View::make('admin.expenditures.expen'.$method, $data);

		}

	}

	private function _create(){
		Larasset::start('footer')->js('bootstrap-datepicker');
		Larasset::start('header')->css('bootstrap-datepicker');
		return array();
	}

	private function _view(){
		$type = Input::get('type', 'created_at');
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
		//$data['entries'] = $data['entries']->orderby($type, 'desc')->paginate(10);
		$data['entries'] = $data['entries']->orderby('date', 'asc')->get();

		$saleslog = Salelog::whereBetween('created_at', array( sqldate($from), sqldate($to) ))
					->select('unitprice', 'costprice', 'quantity', 'total_unitprice')
					->orderby('receipt_id', 'desc')
					->get();

		$profitmargin = 0;

		foreach( $saleslog as $field ){
			//tt( $field->total_unitprice - ( $field->costprice * $field->quantity ) , true);
			$profitmargin += $field->total_unitprice - ( $field->costprice * $field->quantity );
		}

		$data['profitmargin'] = $profitmargin;

		//tt($data);
		return $data;
	}

	public function edit(){
		Larasset::start('footer')->js('bootstrap-datepicker');
		Larasset::start('header')->css('bootstrap-datepicker');
		
		$all = Input::all();
		$id = $all['id'];
		
		//Lets fetch the updated data and return it through ajax
		$data['row'] = Expenditure::where('id', $id)->get()->toArray()[0];

		
		return View::make('admin.expenditures.expen_edit', $data);
	}

	public function saveEdit(){
		$r = Input::all();
		$id = $r['id'];

		if( isset($r['date']) ){
			$r['date'] = sqldate($r['date']);
		}

		unset($r['id']);
		//We update the database
		Expenditure::find($id)->update($r);

		//Lets fetch the updated data and return it through ajax
		$r['id'] = $id;
		
		if( isset($r['date']) ){
			$r['date'] = custom_date_format('M j, Y', $r['date']);
		}
		
		if( isset($r['amount']) ){
			$r['amount'] = format_money($r['amount']);
		}
		
		$data['status'] = 'success';
		$data['message'] = $r;

		return Response::json($data);
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

	public function delete(){
		$ids = explode(',', Input::get('javascriptArrayString'));
		Expenditure::destroy($ids);
		$data['status'] = 'success';
		$data['message'] = count($ids) . ' Row(s) was deleted successfully';
		return Response::json($data);
	}

}