<?php

class Expenditure extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public function user(){
		return $this->belongsTo('User', 'user_id');
	}

	public function getStatusAttribute($value)
	{
		return (int)$value;
	}
}