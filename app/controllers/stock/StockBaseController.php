<?php
class StockBaseController extends \SecureBaseController{
	public $layout 	= 'layouts.2columns';

	public function __construct(){
		parent::__construct();
		$this->filter();
	}

	public function filter(){
		$this->beforeFilter('stock manager');
	}

} 