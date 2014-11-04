<?php

class Customerlog extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'customer_logs';

	public function customer(){
		return $this->belongsTo('customer', 'customer_id');
	}
}