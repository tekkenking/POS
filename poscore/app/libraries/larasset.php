<?php

Class Larasset{

	// Larasset::start()->storeCss($assets);
	// Larasset::start('header')->css('twitter-bootstrap')


	/* CALLING IN THE VIEW  */
	// Larasset::start('header')->show(scripts);
	// Larasset::start('footer')->show(styles);
	// Larasset::start('footer')->show('scripts');
	// Larasset::start('header')->show(styles);

	/* FILTERS METHOD*/
	/*
	* There are two types of Filters method
	* except() and only()
	* 
	* Except() will add all assets of scripts or styles except the listed
	* How to use: Larasset::start('header')->except('bootstrap', 'ace')->show(styles);
	*
	* Only() will add only the selected assets of script or styles
	* How to use: Larasset::start('footer')->only('jquery', 'jquery-ui')->show(scripts);
	*/

	public $vendor_dir = '';
	private $inlineScripts = array();
	public static $assetStoreCss = array();
	public static $assetStoreJs = array();
	private $place;
	private $onlyAssets = NULL;
	private $exceptAssets = NULL;

	public $styles = array();
	public $scripts = array();

	private static $ini = false;

	public static function start($place=''){
		self::$ini = (self::$ini === false ) ? new self : self::$ini;
		self::$ini->place = $place;
		return self::$ini;
	}
	
	//This would input and set the inline JS
	public function set_inlineScript($script){
		$this->inlineScripts[] = $script;
	}
	
	//This would output the inline JS at the bottom of the page
	public function get_inlineScript(){
		if( !empty($this->inlineScripts) ){
			return implode(' ', $this->inlineScripts);
		}
		
		//We have to reset it to free memory
		$this->inlineScripts = array();
	}

	public function __call($method, $args){
		$method = strtolower($method);

		if( $method == 'storecss' || $method == 'storestyle' ){
			$this->_cssBank($args[0]);
		}

		if( $method == 'css' || $method == 'style' ){
			$this->_cssInternal($args);
		}

		if( $method == 'externalcss' || $method == 'externalstyle' ){
			$this->_cssExternal($args);
		}


		if( $method == 'storejs' || $method == 'storescript' ){
			$this->_jsBank($args[0]);
		}

		if( $method == 'js' || $method == 'script' ){
			$this->_jsInternal($args);
		}

		if( $method == 'externaljs' || $method == 'externalscript' ){
			$this->_jsExternal($args);
		}

	}

	private function _cssBank(Array $assetsArray){
		self::$assetStoreCss = array_merge(self::$assetStoreCss, $assetsArray);
	}

	private function _jsBank(Array $assetsArray){
		self::$assetStoreJs = array_merge(self::$assetStoreJs, $assetsArray);
	}

	public function only(){
		$this->onlyAssets = func_get_args();
		return $this;
	}

	public function except(){
		$this->exceptAssets = func_get_args();
		return $this;
	}

	private function resetException(){
		$this->onlyAssets = NULL;
		$this->exceptAssets = NULL;
	}

	public function show($type){
		$this->onlyAssets = ($this->onlyAssets != NULL) ? array_flip($this->onlyAssets) : NULL;
		$this->exceptAssets = ($this->exceptAssets != NULL) ? array_flip($this->exceptAssets) : NULL;


		if( $type == 'styles' ){
			$styles = '';
			if( isset($this->styles[$this->place]) ){
				return $this->_loopOnlyOrExcept( $this->styles[$this->place] );
			}
		}

		if( $type == 'scripts' ){
			$scripts = '';
			if( isset($this->scripts[$this->place]) ){
				return $this->_loopOnlyOrExcept( $this->scripts[$this->place] );
			}
		}
	}
	
	private function _loopOnlyOrExcept($array){
		$type = '';
		foreach( $array as $sets ){
			foreach( $sets as $key => $set ){
				
				if( $this->onlyAssets != NULL &&   $this->exceptAssets == NULL){
					//We work with only();
					if( isset($this->onlyAssets[$key]) ){
						$type .=$set;
					}
				}elseif($this->onlyAssets == NULL &&   $this->exceptAssets != NULL){
					//We work with except();
					if( !isset($this->exceptAssets[$key]) ){
						$type .=$set;
					}
				}else{
					$type .=$set;
				}

			}
		}
		
		$this->resetException();
		return $type;
	}
	
	private function _cssInternal($fileArray){
		//If empty. Return ''
		if( empty($fileArray)){ return '';	}
		$this->_central($fileArray, 'css', 'style', 'styles');
	}

	private function _jsInternal($fileArray){
		//If empty. Return ''
		if( empty($fileArray)){ return '';	}
		$this->_central($fileArray, 'js', 'script', 'scripts');
	}

	private function _cssExternal($fileArray){
		if( empty($fileArray)){ return '';	}
		$this->_central($fileArray, 'css', 'external', 'styles');
	}

	private function _jsExternal($fileArray){
		if( empty($fileArray)){ return '';	}
		$this->_central($fileArray, 'js', 'external', 'scripts');
	}

	private function _central( $fileArray, $type, $network, $assetType ){
		$r = $this->processAssets($fileArray, $type, $network);
		
		$place = $this->place;

		if( $assetType == 'scripts' ){
				$this->scripts[$place][] = $r;
		}elseif( $assetType == 'styles' ){
				$this->styles[$place][] = $r;
		}
	}

	private function processAssets($filesArray, $type, $scr){
		$urlLink = ( $type == 'css' ) ? self::$assetStoreCss : self::$assetStoreJs;
		
		if( empty($urlLink) || !is_array($urlLink) ){
			tt('Nothing in ' . $scr . ' shop!');
			return false;
		}

		$files = array();
		
		foreach($filesArray as $xs){
			$d = strtolower(trim($xs));
			if(isset($urlLink[$d])){
				if( $scr == 'external' ){
					if( $type == 'css' ){
						$files[$d]= '<link href="' . $urlLink[$d] . '" rel="stylesheet" type="text/css" media="all">';
					}elseif( $type == 'js' ){
						$files[$d]= '<script src="' . $urlLink[$d] . '"></script>';
					}
				}else{
					if( !is_array($urlLink[$d]) ){
						$files[$d]= HTML::$scr( $this->vendor_dir . $urlLink[$d] );
					}else{
						$url =  $urlLink[$d]['url'];
						$attributes =  $urlLink[$d]['attr'];
						$files[$d]= HTML::$scr( $this->vendor_dir . $url, $attributes );
					}
				}
			}
		}
		
		return $files;
	}
}