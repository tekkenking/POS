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
		return static::first()->pluck($what);
		//return static::where('id', '=', 1)->pluck($what);
	}


}