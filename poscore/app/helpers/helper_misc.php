<?php

/*
 * --------------------------------------------------------------------
 * Used for outputing 
 * --------------------------------------------------------------------
 *This function displays structured information about one or more expressions that includes its type and value. Arrays and objects are explored recursively with values indented to show structure
 */
if( ! function_exists('tt')){
	function tt($array, $noexit=FALSE, $name='')
	{
		echo "<pre class='alert alert-info'>  {$name} ";
		var_dump($array);
		echo "</pre>";
			if($noexit === FALSE){ exit;}
	}
}

/*
 * --------------------------------------------------------------------
 * Used for outputing 
 * --------------------------------------------------------------------
 *This function displays structured information about one or more expressions that includes its type and value. Arrays and objects are explored recursively with values indented to show structure
 */
if( ! function_exists('copyright_time')){
	function copyright_time()
	{
		return date('Y');
	}
}

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
	Text: extract_char('dhfgd83ks0&9)*^', array('text'), TRUE);
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
		
		//svar_dump($types, true);
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
		//svar_dump($result);
		return $result;
	
	}
}

/*
* Generate the current Date in sql formatted Datetime function
*/ 
if(! function_exists('sqldate') ){
	function sqldate($time='', $format=null){
		if( $format == null ){
			$format = 'Y-m-d H:i:s';
		}

		if($time == ''){
			$time = 'now';
		}

		return date( $format, strtotime($time) );
	}
}

if(! function_exists('custom_date_format')){
	function custom_date_format($format, $date){
		return date($format, strtotime($date));
	}
}

if(! function_exists('format_date')){
	function format_date($date){
		return date('m/d/Y h:i a', strtotime($date));
	}
}

if(! function_exists('format_date2')){
	function format_date2($date){
		return date('jS M, Y', strtotime($date));
	}
}

//Nigeria Date format
if(! function_exists('ng_date_format')){
	function ng_date_format($when='now', $full=false){
		$format = ($full === false ) ? 'd/m/Y' : 'd/m/Y h:i:s A';
		return sqldate($when, $format);
	}
}

//Nigeria Time format
if(! function_exists('ng_time_format')){
	function ng_time_format($when='now', $full=false){
		$format = ($full === false ) ? 'h:i:s A' : 'd/m/Y h:i:s A';
		return sqldate($when, $format);
	}
}

//Nigeria Date/Time format
/* SHOULD REMOVE THIS FUNCTION */
if(! function_exists('ng_datetime_format')){
	function ng_datetime_format($when='now'){
		return sqldate($when, 'd/m/Y h:i:s A');
	}
}

//Nigeria Date/Time format
/* SHOULD REMOVE THIS FUNCTION */
if(! function_exists('ng_datetime_format2')){
	function ng_datetime_format2($when='now'){
		return sqldate($when, 'd/m/Y h:i A');
	}
}


//Birth Date format
// Mon Dec 9th 
if(! function_exists('dob_date_format')){
	function dob_date_format($when='now', $add_year=false){
		if( $add_year !== false ){
			$when = sqldate('today', 'Y') . '-' . $when;
		}
		return sqldate($when, 'M jS');
	}
}

//Ago Date format
// 2mins ago
if(! function_exists('ago_date_format')){
	function ago_date_format($when='now'){
		$now = strtotime('now');
		$when = strtotime($when);
		$diff = $now - $when;

		$_1min 		= 60;
		$_1hour 	= $_1min * 60;
		$_1day 		= $_1hour * 24;
		$_1week 	= $_1day * 7;
		$_1month 	= $_1day * 30;
		$_1year 	= $_1day * 365;

		if( $diff <= $_1min ){ return 'a moment ago'; }
		if( $diff <= $_1hour && $diff > $_1min ){ return timeSinglePlural($diff/$_1min, 'min'); }
		if( $diff <= $_1day && $diff > $_1hour ){ return timeSinglePlural($diff/$_1hour, 'hr'); }
		if( $diff <= $_1week && $diff > $_1day ){ return timeSinglePlural($diff/$_1day, 'day'); }
		if( $diff <= $_1month && $diff > $_1week ){ return timeSinglePlural($diff/$_1week, 'week'); }
		if( $diff <= $_1year && $diff > $_1month ){ return timeSinglePlural($diff/$_1month, 'month'); }
		if( $diff > $_1year ){ return timeSinglePlural($diff/$_1year, 'year'); }
	}
}

if(! function_exists('timeSinglePlural') ){
	function timeSinglePlural($time, $str){

		//tt($time);

		//$time = ($str === 'year') ? ceil($time) : floor($time);
		$time = floor($time);
		$str = ($time >= 2) ? $str . 's' : $str;
		//$str = ($str === 'year' || $str === 'years') ? ' years+' : $str;
		return $time.$str . ' ago';
	}
}

//This would replace all the spaces
if(! function_exists('slug') ){
	function slug($url, $from=" ", $to="+"){
		return implode($to, explode($from, $url));
	}
}

//This would unsually undo a slugged URL
if(! function_exists('urlunslug') ){
	function urlunslug($url, $from='+', $to=' '){
		return slug($url, $from, $to);
	}
}

//This would truncate string
if(! function_exists('truncate_string') ){
	function truncate_string($string, $lenght, $suffix=''){
		$truncstr = substr($string, 0, $lenght);
		if( $suffix != '' ){
			$truncstr = $truncstr . $suffix;
		}

		return $truncstr;
	}
}

if(! function_exists('format_num')){
	function format_num($num, $dec_places=2, $dec_symbol='.', $thousand_group=''){
		return number_format((float)$num, $dec_places, $dec_symbol, $thousand_group);
	}
}

//This would format the currency to money format
if(! function_exists('format_money')){
	function format_money($num){
		return format_num($num, 2, '.', ',');
	}
}

//This would refine the currency figure to 2 decimal figure
if(! function_exists('unformat_money') ){
	function unformat_money($money=0.00){
		return format_num(extract_char($money, array('unformat_money')));
	}
}

//This function groups DB result..
//Argument 1: The DB result [object or Array]
//Argument 2: GroupBy [string]
if(! function_exists('groupThem')){
	function groupThem($data, $name, $orderAsc=False){

		$arrayResultx = array();

		$data = ($orderAsc === False) ? $data : array_reverse($data);
		foreach( $data as $n ){

			//we check if $n is array or object
			$rx = is_array($n) ? $n[$name] : $n->$name;

			$arrayResultx[$rx][] = $n;
		}
			//tt($arrayResultx);
		return $arrayResultx;
	}
}

if(! function_exists('date_remaining')){
	function date_remaining($target, $dt){
		$rt = array(	'day' => array('insecs' => 86400),
						'week'=> array('insecs' => 86400 * 7)
			);

		$today = strtotime('today');
		$target = strtotime($target);
		$result = floor(($target - $today) / $rt[$dt]['insecs']);
		$race = ($result > 1) ? $dt . 's' : $dt;
		return $result . ' ' . $race;
	}
}

if(! function_exists('numberStringOrerBy')){
	function numberStringOrerBy($arrays, $orderby, $asc_desc=SORT_ASC){
		    $sort_col = array();
		    foreach ($arrays as $key=> $row) {
		        $sort_col[$key] = $row[$orderby];
		    }

		   array_multisort($sort_col, $asc_desc, $arrays);

		   return $sort_col;
	}
}

//Function for setting empty barcode. If empty returned from DB
if( ! function_exists('barcodeID') ){
	function barcodeID($code, $defaultStr='******'){
		return ($code === '') ? $defaultStr : $code;
	}
}

//Return Imging
if( ! function_exists('imging') ){

	function imging($name='', $opt=array()){
		$default = array(
				'class' => 'img-polaroid',
				'style' => '',
				'width' => '180px',
				'dir' => Config::get('software.dir_upload_logo'),
				'alt' => '',
			);

		$opt = array_merge($default, $opt);
		$url = $opt['dir']; unset($opt['dir']);
		$alt = $opt['alt']; unset($opt['alt']);

		$url .= ($name === '') ? Config::get('software.default_brand_logo') : $name;

		return HTML::image($url, $alt, $opt);
	}

}

//Return Currency Logo 
if( ! function_exists('currency') ){
	function currency(){
		return Config::get('software.currency_logo');
	}
}

//return Today or date - range [From: 1st Jan 2014 - To: 1 Feb 2014]
if( ! function_exists('display_date_range') ){
	function display_date_range($from, $to, $force = false, $today = 'Today'){

		$from = format_date2($from);
		$to = format_date2($to);

		if( $from === $to && $force === false ){
			return $today;
		}
			
		return "From: ".$from." - To: ".$to;
	}
}