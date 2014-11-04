<?php

class CartController extends \SalesBaseController
{
	//THis method would save the current and new change to the receipt
	public function autoSaveCart(){
		$parts = Input::all();

		//tt($parts);
		$temp = '';
		foreach( $parts as $part => $value ){
			if( $part == 'cart_content' ){
				$temp = Session::get($part, '');

				$break = explode('||', $value);
					if( $break[0] == 'remove' ){
						unset($temp[$break[1]][$break[2]]);
					}else{
						$temp[$break[1]][$break[2]] = $break[0];
					}

				Session::put($part, $temp);
			}else{
				Session::put($part, $value);
			}
		}

		return Response::json(array('saved_cart_response'=>'saved!'));
	}

	public function getMakePayment(){
		return View::make('makepayment');
	}

	public function postMakePayment(){
		
	}

}