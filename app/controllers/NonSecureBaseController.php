<?php

class NonSecureBaseController extends \BaseController{

	public function __construct(){
		parent::__construct();

			$this->beforeFilter('guest');
	}
}