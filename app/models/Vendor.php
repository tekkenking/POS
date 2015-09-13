<?php

class Vendor extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public function user(){
		return $this->belongsTo('User', 'user_id');
	}
}