<?php

class Usersactivity extends Eloquent {
	protected $guarded = array('id');

	public static $rules = array();

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users_activities';

	public function user(){
		return $this->belongsTo('user', 'user_id');
	}
}