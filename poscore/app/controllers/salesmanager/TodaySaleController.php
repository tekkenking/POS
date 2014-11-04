<?php

class TodaySaleController extends \SalesmanagerBaseController {

	//public $filter = 'admin';

	private $tableName = array(
			'sales'=> array('table'=>'salelog', 'field'=>'created_at', 'groupBy'=>'receipt_id', 'paginate'=>10)
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

				//tt($from . '-' . $to);
				$data['todate'] = $to;
		}

		//$dbTableInfo = $this->tableName[$record_type];

		$data['records'] = $this->_dbSyntax($record_type, $from, $to);
		
		$data['fromdate'] = $from;

		return $data;
	}

	private function _dbSyntax($record_type, $from, $to){
		$r_tas = $this->tableName[$record_type];

		if( $record_type === 'sales' ){
			$dbResult = $r_tas['table']::with(array(
				'product'=>function($q){
					  $q->select('id','name', 'productcat_id')
						->with(array('categories'=>function($qr){
						$qr->select('id','type');
						}));
				}, 

				'customer'=>function($q){
					 $q->select('id', 'name');
				},

				'receipt'=>function($q){
					$q->select('id', 'receipt_worth');
				}
				
			))->whereBetween($r_tas['field'], array($from, $to));
			  // ->orderBy('id', 'desc');
		}else{
			$dbResult = $r_tas['table']::whereBetween($r_tas['field'], array($from, $to));
		}

		$dbResult = $dbResult->get()
						->toArray();

		//We groupBy If set for the record search
		if( isset($r_tas['groupBy']) ){
			$dbResult = ($r_tas['groupBy'] === false)
			? $dbResult
			: groupThem($dbResult, $r_tas['groupBy'], true);
		}

		//We paginate if set for the record search
		if( isset($r_tas['paginate']) ){
			$dbResult = Paginator::make($dbResult, count($dbResult), 3);
		}

		return $dbResult;
	}

}