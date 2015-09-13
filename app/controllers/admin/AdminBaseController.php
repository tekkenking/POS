<?php
class AdminBaseController extends \SecureBaseController{
	public $layout 	= 'layouts.2columns';

	public $filter = 'admin';

	public function __construct(){
		parent::__construct();
		$this->filter();
	}

	public function filter(){
		$this->beforeFilter($this->filter);
	}

}