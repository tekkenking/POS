<?php
class SalesBaseController extends \SecureBaseController{
	//public $layout 	= 'layouts.2columns';

	public function __construct(){
		parent::__construct();
		$this->filter();
	}

	public function filter($role='sales'){
		//tt('got');
		$this->beforeFilter($role);
	}

}