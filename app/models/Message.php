<?php

class Message extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	private static $type = array(
			'inbox' => 1,
			'sent' => 2,
			'trashed' => 3
		);

	public static function getUserNewMessageCount(){
		return static::where('checked','=',0)
						->where('to','=',Auth::user()->id)
						->count();
	}

	public static function getIntegerTypeBy($name){
		return static::$type[$name];
	}
}