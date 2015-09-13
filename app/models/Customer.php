<?php

class Customer extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	public $validatorStatus;

	public function customerlog(){
		return $this->hasOne('customerlog', 'customer_id');
	}

	public function receipt(){
		return $this->hasMany('receipt', 'customer_id');
	}	

	public function salelog(){
		return $this->hasMany('salelog', 'customer_id');
	}

	public static function boot(){
		parent::boot();

		//validation on create
		static::creating(function($customer){
			return $customer->isValid();
		});

		//This event will delete all related model in category model
		static::deleted(function($cs){
			//Deletes all customerlog related to a customer
			$c = $cs->customerlog()->lists('id');
			if( !empty($c) ){ Customerlog::destroy($c); }

			//Deletes all Receipt related to a customer
			$r = $cs->receipt()->lists('id');
			if( !empty($r) ){ Receipt::destroy($r); }

			//Deletes all Salelog related to a customer
			$s = $cs->salelog()->lists('id');
			if( !empty($s) ){ Salelog::destroy($s); }
		});

		//validation on update
		static::updating(function($customer){
			//return $customer->isValid();
		});
	}

	public function isValid(){
		Xvalidate::filter('saveCustomer');
		$validation = Xvalidate::these($this->toArray());

		if ( ! $validation->passes() ){
		    	$this->validatorStatus = $validation->messages();
		    	return false;
		    }

		return true;
	}

	public static function getCustomerByID($customer_id, $gettoken=false){
		$customer = static::where('id', '=', $customer_id)->select('name', 'token')->first();

		$name = $customer->name;
		$token = $customer->token;

		return ($gettoken == false) ? $name :  array('customername'=>$name, 'token'=>$token);
	}
}