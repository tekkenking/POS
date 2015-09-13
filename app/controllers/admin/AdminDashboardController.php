<?php

class AdminDashboardController extends AdminBaseController{
	public $layout 	= 'layouts.admin_1column';

	public function index(){
		//Larasset::start('footer')->js('slimscroll');

		//tt('In AdminDashboardController');

		if( Request::ajax() ){
			$method = Input::get('mode');
			$param = Input::get('param');
			$data = $this->$method($param);
			return View::make('admin.'.$method, $data);
		}else{
			$data1 = $this->salesummary();
			$data2 = $this->stockupdate();
			$data3 = $this->lostcustomerssummary();
			$data4 = $this->bestcustomerssummary();

			$data = array_merge($data1, $data2, $data3, $data4);

			$this->layout->title = "Welcome to Admin Area";
			$this->layout->content = View::make('admin.admindashboard', $data);
		}
	}

	private function salesummary($date='salesummary_today'){
		$time = $this->_dateSetter($date);

		$sales = Receipt::whereBetween('created_at', $time )
		->orderBy('id', 'desc');

		$data['salesummary']['sales'] = $sales->get()->toArray();
		$data['salesummary']['totalamount'] = 0.00;
		if( !empty($data['salesummary']['sales']) ){
			$data['salesummary']['totalamount'] = $sales->sum('receipt_worth');
		}
		return $data;
	}

	private function stockupdate($date='stockupdate_today'){
		$time = $this->_dateSetter($date);

		$activities = Usersactivity::where('activity_type','=','stock')
						//->take(10)
						->whereBetween('created_at', $time)
						->orderBy('id', 'desc')
						->get();
		$data = array();

		if( $activities != null ){
			$data['activities'] = $activities;
		}

		return $data;
	}

	private function lostcustomerssummary($type='lostcustomerssummary'){
		if( $type == 'lostcustomerssummary' ){
			//Over one months ago
			$lostCustomers = Customerlog::with('customer')->where('updated_at', '<=', sqldate('yesterday -30 days'))
			//->take(10)
			->orderBy('updated_at', 'asc');

			$data['lostcustomerssummary']['customers'] = $lostCustomers->get();

			//tt( $data['lostcustomerssummary']['customers'] );

			return $data;
		}
	}

	private function bestcustomerssummary($type='bestcustomerssummary'){
		if( $type == 'bestcustomerssummary' ){
			$bestCustomers = Customerlog::with('Customer')
			->take(10)
			->orderBy('alltime_spent', 'desc');

			$data['bestcustomerssummary']['customers'] = $bestCustomers->get()->toArray();

			return $data;
		}
	}

	private function _dateSetter($date){
		$date = str_replace('_', '', strchr($date,'_'));

		if( strtolower($date) == 'lastweek' ){
			return array(sqldate('last week'), sqldate('last week +6 days'));
		}

		if( strtolower($date) == 'lastmonth' ){
			$start_of_last_month = sqldate(date('Y/n/d', strtotime('first day of last month')));
			$end_of_last_month = sqldate(date('Y/n/d', strtotime('last day of last month')));

			return array($start_of_last_month, $end_of_last_month);
		}

		if( strtolower($date) == 'today' ){
			return array(sqldate('today'), sqldate('tomorrow'));
		}

		if( strtolower($date) == 'yesterday' ){
			return array(sqldate('yesterday'), sqldate('today'));
		}

		if( strtolower($date) == 'tomorrow' ){
			return array(sqldate('tomorrow'), sqldate('tomorrow +1 day'));
		}
	}
}