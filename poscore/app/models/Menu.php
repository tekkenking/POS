<?php

class Menu extends Eloquent {
	protected $guarded = array('id');

	public static $rules = array();

	protected $softDelete = true;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'menus';

	public static function getAllMenus(){
		return self::all();
	}
}