<?php

class Salelog extends Eloquent {
	protected $guarded = array('id');

	public static $rules = array();


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'sale_logs';

	/*public function customer(){
		return $this->belongsTo('customer', 'customer_id');
	}*/

	public function receipt(){
		return $this->belongsTo('receipt', 'receipt_id');
	}	

	public function customer(){
		return $this->belongsTo('customer', 'customer_id');
	}

	public function product()
	{
		return $this->belongsTo('product', 'product_id');
	}
}