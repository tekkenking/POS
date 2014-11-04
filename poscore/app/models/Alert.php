<?php

class Alert extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public static function getUserNewAlertCount(){
		return static::where('checked','=',0)
						->where('to','=',Auth::user()->id)
						->count();
	}
}