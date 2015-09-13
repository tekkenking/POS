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

	public function setCustomerIdAttribute($value)
	{
		$this->attributes['customer_id'] = (int)$value;
	}	

	public function setAlltimeQuantityAttribute($value)
	{
		$this->attributes['alltime_quantity'] = (int)$value;
	}	

	public function setAlltimeVisitsAttribute($value)
	{
		$this->attributes['alltime_visits'] = (int)$value;
	}
}