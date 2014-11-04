<?php

class Bankentry extends Eloquent {
	protected $guarded = array('id', 'created_at', 'updated_at');
	//protected $fillable = array('first_name', 'last_name', 'email');

	public static $rules = array();

	/**
	 * The database table used by the model.
	 * @var string
	 */
	protected $table = 'bankentries';

	public function user(){
		return $this->belongsTo('User', 'user_id');
	}
}