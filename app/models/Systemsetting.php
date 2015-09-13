<?php

class Systemsetting extends Eloquent {
	protected $guarded = array('id');

	public static $rules = array();

	/*public static function boot(){
		parent::boot();

		static::updating(function($query){
			
		});
	}

	public function isValid(){

	}*/

	public static function getx($what){
		$dr = static::first();

		return ($dr !== null) ? $dr->pluck($what) : false;
	}


}