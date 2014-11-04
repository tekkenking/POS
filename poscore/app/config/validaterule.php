<?php

return array(
		'login' =>array(
						'username'	=>'required',
						'password'	=>'required',
						),

		'createbrand' => array(
				'brandname' 		=> 'required|unique:brands,name',
				'brandlogo' 		=> 'mimes:jpeg,png,jpg,gif'
						),

		'productcategories' => array(
				'name' => 'required',
				'brand_id' => 'required'
						),

		'saveCustomer' => array(
				'name' => 'required',
				'phone'=> 'required|unique:customers,phone',
				'email'=> 'email|unique:customers,email',
				'mode_id' => 'required',
						),

		'user'			=> array(
				'name'	=> 'required',
				'gender'=> 'required',
				'phone'	=> 'required|unique:users,phone',
				'email' => 'email|unique:users,email',
				'houseaddress' => 'required'
						)
);