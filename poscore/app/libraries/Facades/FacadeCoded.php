<?php namespace Illuminate\Libraries\Facades;


use Illuminate\Support\Facades\Facade;

class FacadeCoded extends Facade {

	protected static function getFacadeAccessor(){
		return 'coded';
	}
}