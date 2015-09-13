<?php

class CartController extends \SalesBaseController
{

	//THis method would save the current and new change to the cart
	public function autoSaveCart(){
		$parts = Input::all();
		$temp = '';

		foreach( $parts as $part => $value ){
			if( $part == 'cart_content' ){
				$temp = Cartsession::getCurrent($part);
				//$temp = Session::get($this->current . '.' . $part, '');

				$break = explode('||', $value);
					if( $break[0] == 'remove' ){
						unset($temp[$break[1]][$break[2]]);
					}else{
						$temp[$break[1]][$break[2]] = $break[0];
					}

				//Session::put($this->current . '.' . $part, $temp);
				Cartsession::saveCurrent($part, $temp);
			}else{
				//Session::put($this->current . '.' . $part, $value);
				Cartsession::saveCurrent($part, $value);
			}
		}

		return Response::json(array('saved_cart_response'=>'saved!'));
	}

	public function getMakePayment(){
		return View::make('makepayment');
	}

	public function suspendCart(){
		Cartsession::suspend();
		return Redirect::back();
	}

	public function resumeCart($key)
	{
		Cartsession::resumeCart($key);
		return Redirect::back();
	}

	public function removeSuspended($key)
	{
		Cartsession::removeSuspended($key);
		return Redirect::back();
	}

	public function previewSuspended($key)
	{
		$datas = Cartsession::getSuspendByKey($key);
		return View::make('suspended_preview_modal', ['items' => $datas])->render();
	}

}