<?php

class Makehash{
	/**
	* This will hold a unique Salt for the hash generator
	*/
	public static $unique_salt = 'uh09e98yu08NJHYt78A89GHt6B87t76t9TBN9O6DASf879GHo9HO';

	/**
	* @ This is a method for Unique and Secure Hash generator.
	* @  HOW TO USE:
		Call the method: Makehash::random().
		NOTE: Calling this method without parameters will return [letters and numbers. LENGHT=50]
	* @ PARAMETERS = 'letter', 'number', 'symbol', 'symletnum', 'all', 'default' and any lenght(number).
		NOTE: 
			-if no lenght is given 50 will be assumed.
			- You can give one parameters. e.g ::generateHash('letters');
				- This will return only letters with lenght=50.
			- If you give only numbers e.g ::generateHash('90') OR ::generateHash(90);
				- This will return a mix of letters and numbers with lenght=90;
			- If you give all parameters. 'all' will overshadow other parameters except the lenght.
			e.g Makehash::generateHash('letters', 'numbers', 'symbols', 'all', 'default', 80);
				- This will return a mix of letters, numbers and symbols with lenght=80.
	* @ return string;
	*
	**/
	public static function random() {
	$args = func_get_args();
	$params = array();
	$hashlenght = array('num'=>50);
		foreach($args as $k=>$key){
			if(is_int($key) OR is_numeric($key)){
				$hashlenght['num'] = (int)intval($key);
				unset($args[$k]);
			}else{
				$params[strtolower($key)] = true;
			}
		}
	
		$allowedArrays = array('letter', 'number', 'symbol','symletnum', 'all', 'default');
		
		// This is to check if nothing is parsed in. And if not letters and numbers are used.
		if($params == null){
			$params['default'] = true;
		}
		
		// This is to check if 'all' is amongst the keys parsed in. And if yes, it will overwrite other parameters
		if(array_key_exists('all', $params)){
				unset($params);
				$params['all'] = true;
			}
		
		//This is to check the types of keys parsed, if they are allowed.
		//svar_dump($params);
		
			foreach($params as $xkey=>$xvals){
				if(!in_array($xkey, $allowedArrays)){
					trigger_error($xkey . ' is not allowed', E_USER_WARNING);
					exit; 
				}
			}
			
		$letter = range('a', 'z');
		$LETTER = range('A', 'Z');
		$number = range('0', '9');
		
		$letter = array_merge($letter, $LETTER);
		$symbol = array('@','*','#','?','=','%','_','-');
		$symletnum = array('_','p','g','-','1','2','4','0');
		
		$randomarrays = array();
		foreach(array_keys($params) as $val){
			if($val == 'all'){
				$randomarrays[] = array_merge($letter, $number, $symbol);
				}elseif($val == 'default'){
				$randomarrays[] = array_merge($letter, $number);
				}else{

				$randomarrays[] = $$val;
			}
		}
		
		$randomVariables = (count($randomarrays) == 1) ? array_shift($randomarrays) : call_user_func_array('array_merge',$randomarrays);
		
		// Lets start the Algorithm
		$hash = array();
		for($i=0; $i<($hashlenght['num']); $i++){
				mt_srand(time()%2938 * 1000000 + (double)microtime() * 1000000);
				$hash[] = $randomVariables[ mt_rand(0, (count($randomVariables)-1)) ];
		}
		
		//This will add to uniqueness layer for the generated hash
		$md5_time = md5(time() . static::$unique_salt);
		
		$extractType = array(
			'number' => 'int',
			'letter' => 'text',
			'symbol' => 'symbol'
		);
		
		$extractorArray = array();
		foreach(array_keys($params) as $val){
			if( isset($extractType[$val]) ){
				$extractorArray[] = $extractType[$val];
			}
		}
		
		$md5_timeArray = str_split( !empty($extractorArray) ? extract_char($md5_time . '*@#%=', $extractorArray) : $md5_time);

		$hash = array_merge($hash, $md5_timeArray);
		shuffle($hash); //Shuffles the hash for security reasons
		$hash = array_reverse($hash); //Reverse the hash for security reasons
		
		$hash = implode('', array_slice($hash, 0, $hashlenght['num']));
		
		return $hash;
	}
}