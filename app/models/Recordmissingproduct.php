<?php

class Recordmissingproduct extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'record_missingproducts';

	public static function getItem($id){
		$r = self::where('product_id', '=', $id)->get();
		if($r != null){ $r = $r->toArray();}
	
		return $r;
	}
}