<?php namespace Libraries;

use Illuminate\Support\Facades\View as View;
use Illuminate\Support\Facades\Lang as Lang;
use Illuminate\Support\Facades\Input as Input;
use Config;

class Coded {

	private $denFile = 'vend.txt';
	private $moment;
	private $fromDayToShowDemoBar=30;
	private $demoDays = '30';

	public function ini_moment($when='now'){
		return $this->moment = new \Moment\Moment($when, Config::get('app.timezone'));
	}

	private function dePath(){
		//return array($_SERVER['SystemRoot'] . '\\'.$denFile, $_SERVER['MIBDIRS'] . "/".$denFile);
		$this->ini_moment();

		//$ddir = !isset($_SERVER['SystemRoot']) ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['SystemRoot'];
		$ddir = $_SERVER['DOCUMENT_ROOT'];
		//dd($ddir);
		return $ddir . '/'. $this->denFile;
	}

	public function deCode(){
		return parse_ini_file( $this->dePath(), true );
	}

	public function isAppSecure(){
		return ( empty($this->deCode()) ) ? false : true;
	}

	public function secureApp(){
		if( file_exists( $this->dePath() ) &&  $this->isAppSecure() ){
			$info = $this->deCode();

			//Lets check license type
			if(strtolower( $info['license']['type'] ) !== 'demo' 
				&&  $this->makeKey($info['pc-info']['installed_time'], $info['pc-info']['hostname']) === $this->makeKey($info['pc-info']['installed_time'], gethostname()) 
				){
					//For mitigating views error
					$this->emptyComposerGNU();

			}else{
				return $this->appDemoAction();
			}

		}else{
			$this->saveAppCode();
			$this->appDemoAction();
		}
	}

	private function emptyComposerGNU(){
		View::composer(array('layouts.footer', 'layouts.admin.admin_footer'), function($view)
		{
		    $view->with('GNU', '');
		});	
	}

	private function composerGNU($remainingDays, $lang, $blade){
		View::composer(array('layouts.footer', 'layouts.admin.admin_footer'), function($view) use($remainingDays, $lang, $blade)
		{
			$warningMsg = Lang::get($lang, array('remaining'=>$remainingDays));
		    $view->with('GNU', View::make($blade, array('gnu_msg'=>$warningMsg)));
		});
	}

	public function appDemoAction(){

		$remainingDays = $this->demoAlert();

		if( (int)$remainingDays <= 0 ){
			//Lock App for license code
			$this->composerGNU($remainingDays, 'general.gnu_timeout', 'error.gnu_timeout');
			return;
		}

		if( (int)$remainingDays <= $this->fromDayToShowDemoBar  ){
			//Alert warning at the bottom bar
			$this->composerGNU($remainingDays, 'general.gnu_warning', 'error.gnu_warning');
			return;
		}

		//To mitigate error when in demo and hiding the demo-alert-navbar
		$this->composerGNU($remainingDays, 'general.gnu_timeout', 'error.gnu_empty');

	}

	public function demoAlert($infx =''){
		$info = ($infx === '') ? $this->deCode() : $infx;

		$expiredDate = $info['license']['demo_expired_date'];

		$this->ini_moment();

		$today = $this->moment->from( $expiredDate );

		return (int)$today->getDays();
	}

	private function saveAppCode(){		
		$this->ini_moment();

		$hostname = gethostname();
		$data ="[pc-info]";
		$data .= "\nhostname = " . $hostname;
		$data .= "\ninstalled_time = " . $this->moment->format();
		$data .= "\nmachine_code = " . $this->makeMachineCode();
		$data .= "\n[license]";
		$data .= "\ntype = Demo";
		$data .= "\ncode = *************";
		$data .= "\ndemo_expired_date = " . $this->moment
												->addDays($this->demoDays)
												->format();

		file_put_contents( $this->dePath(), $data);
	}

	private function makeMachineCode(){
		return $this->keyStringReplacer( $this->makeKey('now', gethostname(), 10) ); 
	}

	private function makeKey($itime, $hname, $length=28){
		$code = md5(strtotime($itime) . $hname);
		//tt($code);
		return substr( $this->keyStringReplacer( $code ), 0, 28);
	}

	private function keyStringReplacer($code){
		$find =  array_merge( range(0,9), range('A', 'Z') );
		$put =  array( '9','D','1','U','F','E','S','T','N','7','J','8','Y','X','A','V','P','W','2','H','I','6','B','4','C','5','R','3','0','K','L','O','G','Z','M','Q' );
		return str_replace($find, $put, $code);
	}

	public function activateApp($input){
		$info = $this->deCode();
		$info['license']['code'] = $this->makeKey($info['pc-info']['installed_time'], gethostname());

		$input = substr( $this->keyStringReplacer( str_replace('-', '', $input) ), 0, 28);

		if( strtoupper($info['license']['code']) === strtoupper(trim($input)) ){
			$info['license']['type'] = 'activated';

			foreach( $info as $group => $value){
				@$data .= "[" .$group . "]\n";
					foreach($value as $k => $v){
						$data .=$k . ' = ' . $v ."\n";
					}
			}

			file_put_contents( $this->dePath(), $data);

			return true;
		}else{
			return false;
		}
	}

	private function readCode(){
		return $this->deCode();
	}
}