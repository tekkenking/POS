<?php namespace App\Libraries\Facades;


use Illuminate\Support\Facades\Facade;

class FacadecartSession extends Facade {

	protected static function getFacadeAccessor(){
		return 'cartsession';
	}
}