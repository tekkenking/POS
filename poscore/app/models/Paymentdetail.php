<?php

class Paymentdetail extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public function receipt(){
		return $this->belongsTo('receipt', 'receipt_id');
	}
}