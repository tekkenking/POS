<?php

class Xvalidate
{
	
	protected static $rules = FALSE;
	protected static $filters = array();
	
	/**
	* Method to store valdiation filter rules
	* @ Accepts String [ REQUIRED ]
	**	-format 1[ filter: login + username password ] ( NON GREEDY: Take from login the listed after **the + sign )
	**	-format 2[ filter: login - username password ] ( PARTIAL GREEDY: takes all rules at login **exclude username )
	**	-format 3[ filter: login] ( GREEDY FORMAT: takes all the rules at login )
	*/
	public static function filter($rules)
	{ 
		static::$filters[] = $rules;
	}

	public static function custom_rule($array_rules){
		static::$rules = $array_rules;
	}

	/**
	* This method does the main work of the validation
	* @ Accepts three parameters
	* @ Param 1 = Array [REQUIRED]
	* @ Param 2 = String or Array [PARTIAL REQUIRED]
	*	-String 
	*		-format 1[ filter: login + username password ] ( NON GREEDY: Take from login the listed *after the + sign )
	*		-format 2[ filter: login - username password ] ( PARTIAL GREEDY: takes all rules at login *exclude username )
	*		-format 3[ filter: login] ( GREEDY FORMAT: takes all the rules at login )
	*	-Array
	*		-format[ Just the array of validation rules ]
	* @ Param 3 = Custom error message for validation rules parsed [ OPTIONAL ]
	* @ Return mix
	*/
	public static function these($data, $type='', $message='')
	{
		$rules = array();
		static::$rules = (static::$rules === FALSE) ? Config::get('validaterule') : static::$rules;

		if(!empty($data))
		{
			//Lets check the type of filter validation
			$house_filters = array();
			
			if( !empty($type) && is_string($type)){
				$type = trim($type);
				$house_filters[] = $type;
			}

			$join_filters = array_merge(static::$filters, $house_filters);

			$rules = static::workfilter($join_filters); 

			if(!empty($type) && is_array($type)){
				if( !empty($rules) ){
					$rules += $type;
				}else{
					$rules = $type;
				}
			}

			//We have to reset the static rules property. VERY IMPORTANT LINE
			static::$rules = FALSE;

			return ($message == '') ? Validator::make($data, $rules) : Validator::make($data, $rules, $message);
		}
	}

	/**
	* Array of validation filters from these()
	*/
	private static function workfilter($break)
	{
		$rules = array();
		foreach($break as $key){
			$rules += static::common_filter_function(trim($key));
		}
		return $rules;
	}

	private static function common_filter_function($type)
	{ 
		$secondbreak = explode(' ', $type);
		$validation_key = array_shift($secondbreak); // The array key in the RULES
		$filter_sign = array_shift($secondbreak); // Either to minus or plus from the array array RULES
		
		$rules = array();
		//If filter_sign = +
		if( $filter_sign == '+' ){
			foreach ($secondbreak as $key) {
				$rules[$key] = static::$rules[$validation_key][$key];
			}
			
			return $rules;
		}

		//If filter_sign = -
		if( $filter_sign == '-' ){
			foreach ($secondbreak as $key) {
				unset(static::$rules[$validation_key][$key]);
			}

			return static::$rules[$validation_key];
		}

		if( $filter_sign == null || $filter_sign == 'all' ){
			return static::$rules[$validation_key];
		}
	}
}