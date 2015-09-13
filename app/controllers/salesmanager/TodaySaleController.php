<?php

class TodaySaleController extends \SalesmanagerBaseController {

	//public $filter = 'admin';

	private $tableName = array(
			'sales'=> array('table'=>'receipt', 'field'=>'created_at', 'groupBy'=>'id', 'paginate'=>30)
		);


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		Larasset::start('header')->css('daterangepicker');
		Larasset::start('footer')->js('moment', 'daterangepicker');
		Larasset::start('footer')->js('dataTables-min', 'dataTables-bootstrap', 'bootstrap_editable');

		//Setting Defaults
		$data['records'] ='';
		$data['fromdate'] = '';
		$data['todate'] = sqldate('now');
		$data = $this->_fetchRecord();
		$data['view_file'] = 'sales.sales';

		//Geting dynamic
		if( ($record_type = Input::get('record_type')) != NULL ){
			//$data = $this->_fetchRecord();
			$data['view_file'] = 'sales.' . $record_type;
		}

		$this->layout->title = "Sales records";
		$this->layout->content = View::make('sales.sales_index', $data);
	}

	private function _fetchRecord(){
		$record_type = ( Input::get('record_type') != NULL ) ? Input::get('record_type') : 'sales';
		$daterange = (Input::get('record_range') !== NULL) ? Input::get('record_range') : '';

		if( strpos($daterange, '-') !== FALSE ){
				$fromandto = explode('-', $daterange);
				$from = sqldate($fromandto[0]);
				$to_plus_1day = (strtotime('now') - strtotime('yesterday')) + strtotime($fromandto[1]);
				$to = sqldate(date('Y/n/d', $to_plus_1day ));
				$data['todate'] = $fromandto[1];
			}else{
				$from = sqldate('today');
				$to = sqldate('now');
				$data['todate'] = $to;
		}

		//$dbTableInfo = $this->tableName[$record_type];

		$data['records'] = $this->_dbSyntax($record_type, $from, $to);
		
		$data['fromdate'] = $from;

		return $data;
	}

	private function _dbSyntax($record_type, $from, $to){
		$r_tas = $this->tableName[$record_type];

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

		//tt($m->get()->toArray());


			$totalAmount = $m->sum('amount_tendered');
			View::composer('admin.records.records_sales', function($view) use ($totalAmount){
				$view->with('sumAmountTendered', $totalAmount);
				//$view->with('sumAmountDue', $m->sum('salelogs.total_unitprice'));
			});
		return $m->paginate($r_tas['paginate']);
	}

}