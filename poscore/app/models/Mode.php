<?php

class Mode extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	//private $modeStatusName = array(1=>'Enabled', 2=>'Disabled');

	private static $modeName;


	public static function getModeNameFromID($id){
		static::$modeName = static::all()->lists('name', 'id');

		return static::$modeName[$id];
	}

	public static function getModeIDFromName($name){
		$name = strtolower(str_replace(' ', '', $name));
		static::$modeName = static::all()->lists('id', 'name');
		return static::$modeName[$name];
	}

	public static function listModes(){
		return static::where('status','=',1)->lists('name', 'id');
	}
}