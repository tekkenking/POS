<?php

/*
 * --------------------------------------------------------------------
 * Used to extract character type specified from a string
 * --------------------------------------------------------------------
 * Accepts three parameters.
 * First two a required. Third is optional
 * First [string]: the string of characters to extract from
 * Second [array]: Array of Extraction type
 * Third [string]: The string to replace the match
 * Forth [bool]: Wether to extract all matched or first matched 
 * E.g 
	Text: extract_char('dhfgd83ks0&9%*^', array('text'), TRUE);
 * 
 */
 
if( ! function_exists('extract_char')){
	//Please do not parse in float and INT as you'd get unexpected result
	function extract_char($string='', Array $type=null, $rep='', $single=false)
	{
		if( $string == ''){
			trigger_error(__FUNCTION__ . ' Requires 1 string parameter', E_USER_WARNING);
				return;
		}

		$allowedTypes = array(	'float'		=>	'([\d]+\.[\d]+)|', 
								'int'		=>	'([\d]+)|', 
								'text'		=>	'([a-zA-Z \s]+)|', 
								'symbol'	=> 	'([^\s\t0-9a-zA-Z])|',
								'symbol2'	=>	'([\_\-\@])',
								'html_tag'	=> 	'(<[^<>]+>)|',
								'unformat_money' => '([0-9\.-]+)'
							 );
		$allowedkey = $type == null ? array('text') : $type;
		
		$types = '';
		foreach( $allowedkey as $key ){
			$key = strtolower($key);
			if( isset($allowedTypes[$key]) ){
				$types .= $allowedTypes[$key];
			}else{
				trigger_error($type . ': [' . $key . '] is not allowed. text is assumed', E_USER_NOTICE);
				$types .= $allowedTypes['text'];
			}
		}
		
		if( $rep != '' ){
				$result = preg_replace("/$types/", $rep, $string);
		}else{
			if( $single == true ){
				preg_match("/$types/", $string, $match);
			}else{
				preg_match_all("/$types/", $string, $match);
			}

			$result = implode('', $match[0]);
		}

		return $result;
	
	}
}