<?php

class AdminStockupdateRecordController extends AdminBaseController
{

	public $layout 	= 'layouts.admin_1column';

	public function stockUpdate()
	{
		Larasset::start('header')->css('daterangepicker');
		Larasset::start('footer')->js('moment', 'daterangepicker');

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

		$data['fromdate'] = $from;
		$data['todate'] = $to;

		$time = array( $data['fromdate'], $data['todate'] );

		$activities = Usersactivity::where('activity_type','=','stock')
						//->take(10)
						->whereBetween('created_at', $time)
						->orderBy('id', 'desc')
						->get();
		//$data = array();

		if( $activities != null ){
			$data['activities'] = $activities;
		}

			$this->layout->title = "Welcome to Admin Area";
			$this->layout->content = View::make('admin.stockupdate_record.index', $data);
	}
}