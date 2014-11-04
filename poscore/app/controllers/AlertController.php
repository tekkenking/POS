<?php

class AlertController extends \SecureBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	/*public function index()
	{
		$time = array(sqldate('yesterday -5 days'), sqldate('now'))
		$alerts = Alerts::whereBetween('created_at',$time)
					->orWhere('checked','=',0)
					->orderBy('checked', 'asc')
					->orderBy('id', 'asc');

		$data['alert'] = $alerts->get()->toArray();

		tt($data);
	}*/

	//Return Ajax count of listed our of stock items
	public function productAlmostOutOfStock_count(){
		$data = $this->productAlmostOutOfStock('count');
		return Response::json($data);
	}

	//Return Ajax template of listed our of stock items
	public function productAlmostOutOfStock_list(){
		$data = static::productAlmostOutOfStock('list');
		return View::make('almostoutofstock_list', $data);
	}

	public static function productAlmostOutOfStock($type){
		$almost_out_of_stock = Product::with(					
								array(

									'brand'=> function($query){
										$query->select('id', 'name');
									}, 

									'categories'=> function($query){
										$query->select('id', 'name');
									}

									)
										)->select('id', 
												'name', 
												'quantity',
												'productcat_id', 
												'brand_id', 
												'almost_finished', 
												'published')
										->orderBy('quantity', 'asc')
										->get()
										->toArray();

		//tt($almost_out_of_stock);

		$counter=0; // Counter for count alert
		if($type === 'list' || $type === 'all'){$data['products'] = array();}

		if( !empty($almost_out_of_stock ) ){
			foreach ($almost_out_of_stock as $key) {
				if($key['published'] != 0 && $key['quantity'] <= $key['almost_finished'] && $key['almost_finished'] > 0){
					//We'll take record is we're requesting for listing the items
					if($type === 'list' || $type === 'all'){
						$key['linktoproduct'] =  (User::permitted('role.stock manager')) 
												? URL::route('adminShowProduct', array(slug($key['brand']['name']), slug($key['categories']['name'])))
												:'';

						//tt($key);
						$data['products'][] = $key;
					}
					$counter++;
				}
			}
		}

		$data['count'] = $counter;
		//tt($data['products']);
		return $data;
	}

	public function unreadInboxMessageAlert_count(){
		$data = static::unreadInboxMessageAlert('count');
		return Response::json($data);
	}

	public function unreadInboxMessageAlert_list(){
		$data = static::unreadInboxMessageAlert('list');
		return View::make('messagealert_list', $data);
	}

	public static function unreadInboxMessageAlert($type){
		$new = Message::where('to','=',Auth::user()->id)
						->where('checked', '=', 0)
						->where('type', '=', 1);

		$data['count'] = $new->count();

		if( $type === 'list' || $type === 'all' ) $data['messages'] = $new->get()->toArray();

		return $data;
	}

	public static function birthdayAlert(){
		$from = sqldate('today');

			$to = (int)Systemsetting::getx('dob_alert_day');
			$to = ($to >= 1) ? '+' . $to . 'days' : 'today';
			$to = $to;

		$to = sqldate($to);

		//tt($to);
		
		$Customers_bday = Customer::with('customerlog')->whereBetween('birthday', array($from, $to));

		$data['count'] = $Customers_bday->count();
		$data['customer_dob'] = $Customers_bday->orderBy('birthday', 'asc')
												->get()
												->toArray();

		//tt($data['customer_dob']);
		
		return $data;
	}

}