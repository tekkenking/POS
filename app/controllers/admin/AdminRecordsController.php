<?php

class AdminRecordsController extends AdminBaseController{

	private $tableName = array(
			'sales'=> array('table'=>'receipt', 'field'=>'created_at', 'groupBy'=>'id', 'paginate'=>30),
			'customers' => array('table'=>'customerlog', 'field'=>'updated_at', 'groupBy'=>false, 'orderBy'=>'alltime_spent', 'orderType'=>'desc'),
			'stocks' => array('table'=>'stocklog', 'field'=>'created_at', 'groupBy'=>false)
		);

	public function index(){
		Larasset::start('header')->css('daterangepicker');
		Larasset::start('footer')->js('moment', 'daterangepicker');
		Larasset::start('footer')->js('dataTables-min', 'dataTables-bootstrap', 'bootstrap_editable');

		$data['records'] ='';
		$data['fromdate'] = sqldate(date('Y/n/d', strtotime('first day of this month')));
		$data['todate'] = sqldate('+1day');

		if( ($record_type = Input::get('record_type')) != NULL ){
			$data = $this->_fetchRecord();
			$data['view_file'] = 'admin.records.records_' . $record_type;
		}
		
		$this->layout->title = 'Records Area';
		$this->layout->content = View::make('admin.records.records_index', $data);
	}
 
	private function _fetchRecord(){
		$record_type = Input::get('record_type');
		$daterange = Input::get('record_range');

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

		$data['records'] = $this->_dbSyntax($record_type, $from, $to);

		//tt($data['records']->toArray());

		$data['fromdate'] = $from;

		return $data;
	}

	private function _dbSyntax($record_type, $from, $to){
		$r_tas = $this->tableName[$record_type];

		if( $record_type === 'sales' ){
			$dbResult = $r_tas['table']::with([


				'salelogs'	=>	function($sl){
					$sl->with([

						'product'=>function($q){
							  $q->with(array('categories'=>function($qr){
									$qr->select('id','type');
								}))
								->select('id','name', 'productcat_id');
						}, 

						'customer'=>function($q){
							 $q->select('id', 'name');
						}


					]);
				}])->whereBetween($r_tas['field'], array($from, $to))
			->orderBy('created_at', 'desc');

			$m = $dbResult;
			$totalAmount = $m->sum('amount_tendered');
			View::composer('admin.records.records_sales', function($view) use ($totalAmount){
				$view->with('sumAmountTendered', $totalAmount);
				//$view->with('sumAmountDue', $m->sum('salelogs.total_unitprice'));
			});

			//tt( $m->sum('amount_tendered') );

			return $m->paginate($r_tas['paginate']);

		}else{
			return $r_tas['table']::whereBetween($r_tas['field'], array($from, $to))
					->get()
					->toArray();
		}
	}
}