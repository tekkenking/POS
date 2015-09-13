<?php

class Role extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public $timestamps = false;

	public function users(){
    	return $this->belongsToMany('user');
    }
}