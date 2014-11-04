<?php
class SalesmanagerBaseController extends \SecureBaseController{
	//public $layout 	= 'layouts.2columns';

	public function __construct(){
		parent::__construct();
		$this->filter();
	}

	public function filter($role='sales manager'){
		//tt('got');
		$this->beforeFilter($role);
	}

}