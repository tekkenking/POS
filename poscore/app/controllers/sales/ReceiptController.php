<?php

class ReceiptController extends \SalesBaseController{

	private $receiptID;

	/*public function receiptDetails(){
		if( Session::get('receipt_number', null) == null ){
			//Lets auto generate receipt number
			$lastReceiptNumber = Receipt::last();
		}

	}*/

	public function previewReceipt(){
		//tt(Session::get('cart_token', '') . '=>' . $previewreceipt);
		/*if( Session::get('cart_token') != $previewreceipt ){
			return Redirect::route('home');
		}*/

		//$this->layout->title = 'Preview Receipt';
		//Larasset::start('footer')->js('jquery-print');
		return View::make('receipt_preview');
			
	}

	public function popoverReceiptPreview($receipt_id){
		$r = Receipt::where('id', $receipt_id)->first();

		$data['transaction'] = Receipt::buildReceipt($receipt_id);
		$data['created_date'] = ng_date_format($r->created_at);
		$data['created_time'] = ng_time_format($r->created_at);
		$data['sales_person'] = istr::title(User::where('id', $r->user_id)->first()->name);
		$data['total_amount_due'] = format_money($r->receipt_worth);
		$data['total_amount_tendered'] = format_money($r->amount_tendered);
		$data['change'] = format_money($r->change);

		$s = Salelog::where('receipt_id', $receipt_id)->get()->toArray();
		$data['items'] = $s;

		return View::make('admin.quick_receipt_preview', $data);
	}

	//We'll unset all the cart and receipt session keys
	//We'll update the quantity table
	//We'll save the receipt like snapshot
	//We'll keep sales log
	public function saveReceipt()
	{
		$data = Input::all();

		//We update log of "All time customers", Sales record
		$customer_id = $this->updateCustomerlog($data['others']);

		//saveReceiptToTable
		$this->saveReceiptToTable($data['others'], $customer_id);

		//Save payment details
		$this->savePaymentDetails($data['paymentdetails']);
		unset($data['paymentdetails']);

		//updateProductTable
		$datax = $data;
		unset($datax['others']);
		
		$this->updateProductTable($datax);

		//Keep sale log
		$this->keepSalesLog($data, $customer_id);

		//EmptyCart
		$this->emptyCart();

		$ajax['status'] = 'success';
		$ajax['message'] = 'Operations completed';
		$ajax['receipt_number'] = Receipt::buildReceipt($this->receiptID);

		//Lets work the datetime
		$ajax['receipt_date'] = ng_date_format($this->receipt_created_at);
		$ajax['receipt_time'] = ng_time_format($this->receipt_created_at);
		
		return Response::json($ajax);
	}

	//Return Customer_ID: Integer;
	private function updateCustomerlog($data){
		$customer_id = $this->getCustomerID($data['customer_token']);
		$customer_id = ($customer_id != null && $customer_id != 0) ? $customer_id : 'anon' . strtotime('now');

		if( $customer_id >= 1 ){
				$customerlog = Customer::find($customer_id)->customerlog;
				if( $customerlog == null ){
					$newCustomerlog = new Customerlog;
					$newCustomerlog->customer_id = $customer_id;
					$newCustomerlog->alltime_spent = $data['totalamount'];
					$newCustomerlog->alltime_quantity = $data['totalquantity'];
					$newCustomerlog->alltime_visits = 1;
					$newCustomerlog->save();
				}else{
					$customerlog->alltime_spent += $data['totalamount'];
					$customerlog->alltime_quantity += $data['totalquantity'];
					$customerlog->alltime_visits += 1;
					$customerlog->save();
				}
		}

		return $customer_id;
	}

	//NEED MORE TESTING WITH MULTIPLE COMPUTERS AND TRY TO PRINT RECEIPT SIMOUSTENIOUSLY
	private function saveReceiptToTable($data, $customer_id){
		$data['receipt_number'] = Receipt::current();// We generate Receipt Number on fly
		$receiptID = Receipt::create(
			array(
					'customer_id' 				=> $customer_id,
					'user_id' 					=> Auth::user()->id,
					'receipt_worth' 			=> $data['totalamount'],
					'receipt_subtotalamount' 	=> $data['subtotalamount'],
					'vat'						=> $data['vat'],
					'vat_worth' 				=> $data['vat_price'],
					'amount_tendered' 			=> $data['amount_tendered'],
					'change'					=> $data['change']
				)
			);

		//We set the receipt inserted ID
		$this->receiptID = $receiptID->id;

		//We set the receipt created_at datetime
		$this->receipt_created_at = $receiptID->created_at;

		//Save the current Sale activity
		$this->saveActivity($data['totalamount'], $customer_id);
	}

	private function saveActivity($totalamount, $customer_id){
		$customer = Customer::find($customer_id);
		$customername = ($customer != null ) ? $customer->pluck('name') : null;
		doUserActivity::saveActivity('sale', $totalamount, $customername , $this->receiptID );
	}

	private function savePaymentDetails($paymentdetails){
		
		foreach( $paymentdetails['paymentmethods'] as $tr ){
			$pd = new Paymentdetail;
			$pd->receipt_id = $this->receiptID;
			$pd->method = $tr['method'];
			$pd->amount = $tr['amount'];
			$pd->reference = $tr['reference'];
			$pd->save();
		}
	}

	private function updateProductTable($data){
		//tt($data, true);
		foreach($data as $index => $array){
			foreach( $array as $id=>$dataArray ){
				foreach( $dataArray as $field => $value ){
					$product = Product::find($id);

					if( $field == 'quantity' ){
						$product->quantity = $product->quantity - $value;

						if( $dataArray['cat_type'] === 'service' )
						{
							$product->quantity = 0;	
						}
					}

					if( $field == 'total_unitprice' ){
						$modeTotalprice = $this->modename($dataArray['salesmodename']) . '_totalprice';

						$product->$modeTotalprice = $product->$modeTotalprice - $value;
					}

					$product->save();
				}
			}
		}
	}

	private function keepSalesLog($data, $customer_id){
		$others = $data['others'];
		unset($data['others']);

		//tt($others);
		foreach($data as $index=>$array){
			foreach( $array as $id => $dataArray ){
				$sale = new Salelog;
				$sale->product_id = $id;
				$sale->receipt_id = $this->receiptID;
				$sale->mode_id = $this->modeID($dataArray['salesmodename']);
				$sale->user_id = Auth::user()->id;
				$sale->customer_id = $customer_id;
				//$sale->saletime = strtotime('now');
				unset($dataArray['salesmodename']); // Unset salemodename before db logging
				unset($dataArray['cat_type']); // Unset cat_type before db logging
					foreach( $dataArray as $field => $value ){
						$sale->$field = $value;
						//tt($field . '=>' . $value, true);
					}
				$sale->save();
			}
		}
	}

	public function emptyCart(){
		Session::forget('cart_content'); // Cart Items
		Session::forget('subtotalamount');
		Session::forget('vat');
		Session::forget('customer_token');
		Session::forget('totalamount');
		Session::forget('enteroveralldiscount');
		Session::forget('totalamounttendered');
		//Session::forget('receipt_number');
		//Session::forget('cart_token');
		Session::forget('cart_quantity');

		//Session::put('receipt_saved', 1);
		//Session::put('save_in_progress', 0);
	}

	public function getCustomerID($info){
		if( strlen($info) > 5){
			$explode = explode('|', $info);
			$token = extract_char($explode[1], array('int'));
			return Customer::where('token', '=', $token)->pluck('id');
		}else{
			return 0;
		}
	}

	public function modename($salesmodename){
		$name = str_replace(' ', '', $salesmodename);
		$name = strtolower($name);
		return Mode::getModeNameFromID(Mode::getModeIDFromName($name));
	}

	public function modeID($salesmodename){
		$name = str_replace(' ', '', $salesmodename);
		$name = strtolower($name);
		return Mode::getModeIDFromName($name);
	}

}