<?php

class Receipt extends Eloquent {

	protected $guarded = array();

	public static $rules = array();

	private static $prefix = '1000';

	private static $receipt_number;

	public function salelogs(){
		return $this->hasMany('salelog', 'receipt_id');
	}

	public function paymentdetails(){
		return $this->hasMany('paymentdetail', 'receipt_id');
	}

	public function user(){
		return $this->belongsTo('user', 'user_id');
	}

	public function customer(){
		return $this->belongsTo('customer', 'customer_id');
	}

	public static function lastID($receiptID=''){
		return ($receiptID === '') ? static::max('id') : $receiptID;
	}

	public static function last(){
		$r = static::lastID();
		return static::$receipt_number = static::$prefix + (($r != null) ? $r : 0);
	}

	public static function buildReceipt($receiptID){
		return static::$prefix . $receiptID;
	}

	public static function current(){
		if( Session::get('receipt_number', '---') == '---' ){
			$r = static::last() + 1;
			Session::put('receipt_number', $r);
			return $r;
		}

			return Session::get('receipt_number', '---');
	}

}