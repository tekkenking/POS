<?php

class BaseController extends Controller {

	public $restful = true;

	public $layout 	= 'layouts.column';

	public function __construct(){
		//tt(Makehash::random('symletnum',6));
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{

		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
			$this->setupAssets();
			Coded::secureApp();
		}
	}


/***************** ASSETS RELATED STARTS ********************/
	protected function setupAssets(){
		$css = array(
				'twitter-bootstrap-docs' => 'twitter-bootstrap-2.3.2/css/docs.css',
				'twitter-bootstrap' => 'twitter-bootstrap-2.3.2/css/bootstrap.css',
				'font-awesome' => 'font-awesome-3.2.1/css/font-awesome.min.css',
				'twitter-bootstrap-responsive' => 'twitter-bootstrap-2.3.2/css/bootstrap-responsive.css',
				'ace-font' 	=> 'acetheme/css/ace-fonts.css',
				'ace-responsive' => 'acetheme/css/ace-responsive.min.css',
				'ace-skins' => 'acetheme/css/ace-skins.min.css',
				'ace' 		=> 'acetheme/css/ace.min.css',
				'bucketcodes' 	=> 'bucketcodes/css/bucketcodes.css',
				'magicsuggest' => 'nicolasbize-magicsuggest/src/magicsuggest-1.3.1.css',
				//'fuelux' => 'fuelux/css/fuelux.css',
				'bootstrap_editable' => 'bootstrap_editable/bootstrap-editable/css/bootstrap-editable.css',
				//'selectboxit' => 'jquery.selectboxit/3.7.0/jquery.selectboxit.css',
				'bootstrap-datepicker' => 'acetheme/css/datepicker.css',
				'select2' => 'acetheme/css/select2.css',
				'daterangepicker' => 'acetheme/css/daterangepicker.css',
				'tkeyboard' => 'bucketcodes/css/tkeyboard.css',
				'vegas'=> 'vegas-master/src/jquery.vegas.css'
			);

		$js = array(
				'jquery' => 'jquery/jquery-2.0.3.min.js',
				'twitter-bootstrap' => 'twitter-bootstrap-2.3.2/js/bootstrap.js',
				'ace-element' => 'acetheme/js/ace-elements.min.js',
				'ace' => 'acetheme/js/ace.min.js',
				//'fuelux.spinner' => 'acetheme/js/fuelux.spinner.min.js',
				'jquery-ui' => 'acetheme/js/jquery-ui-1.10.3.custom.min.js',
				'ajax-request' => 'bucketcodes/js/ajax-request.js',
				'ajax-request-wrapper' => 'bucketcodes/js/ajax-request-wrapper.js',
				'ajax-refresh' => 'bucketcodes/js/ajax-refresh.js',
				'commonjs' => 'bucketcodes/js/common.js',
				'g56_functions' => 'bucketcodes/js/g56_functions.js',
				'bootbox' => 'acetheme/js/bootbox.js',
				'datatables-bootstrap' => 'acetheme/js/jquery.dataTables.bootstrap.js',
				'datatables-min' => 'acetheme/js/jquery.dataTables.min.js',
				'debugger' => 'bucketcodes/js/js-debugger.js',
				'bootstrap-growl' => 'bootstrap-growl/bootstrap-growl.js',
				'magicsuggest' => 'nicolasbize-magicsuggest/src/magicsuggest-1.3.1.js',
				'fuelux-loader-required' => 'fuelux/js/loader.min.js',
				'bootstrap_editable' => 'bootstrap_editable/bootstrap-editable/js/bootstrap-editable.js',
				//'selectboxit' => 'jquery.selectboxit/3.7.0/jquery.selectboxit.min.js',
				'jquery-print' => 'jquery.plugin.print/jquery.plugin.print.js',
				'jquery-cookies' => 'bucketcodes/js/jquery_cookies.js',
				'bootstrap-datepicker' => 'acetheme/js/bootstrap-datepicker.min.js',
				'select2' => 'acetheme/js/select2.min.js',
				'slimscroll' => 'acetheme/js/jquery.slimscroll.min.js',
				'moment' => 'acetheme/js/moment.min.js',
				'daterangepicker' => 'acetheme/js/daterangepicker.min.js',
				'maskedinput' => 'acetheme/js/jquery.maskedinput.min.js',
				'tkeyboard' => 'bucketcodes/js/tkeyboard.js',
				'caret' => 'bucketcodes/js/jquery.caret.js',
				'ionsound' => 'ionsound/js/ion-sound/ion.sound.min.js',
				'vegas'=> 'vegas-master/src/jquery.vegas.js'
			);

	Larasset::start()->vendor_dir = 'vendor/';
	Larasset::start()->storecss($css);
	Larasset::start()->storejs($js);

	Larasset::start('header')->css('twitter-bootstrap-docs', 'twitter-bootstrap', 'twitter-bootstrap-responsive', 'font-awesome', 'selectboxit', 'magicsuggest', 'ace-font', 'ace','ace-responsive', 'ace-skins', 'bucketcodes', 'tkeyboard');

	Larasset::start('header')->js('jquery');
	Larasset::start('footer')->js('jquery-ui','twitter-bootstrap', 'ajax-request','ajax-request-wrapper', 'ajax-refresh', 'bootbox','debugger', 'ace-element', 'ace', 'commonjs', 'g56_functions', 'magicsuggest', 'caret', 'tkeyboard','jquery-print', 'ionsound', 'slimscroll', 'bootstrap-growl');
	}

/***************** ASSETS RELATED ENDS ********************/

	public function callErrorPage($num='404'){
			$this->layout->title = $num .' Page error!';
			$this->layout->content = View::make('error.' . $num);
			return;
	}

	public function updateisLoggedin($value){
		if( Auth::check() ){
			$user = User::find(Auth::user()->id);
			$user->isloggedin = $value;
			$user->save();
		}
	}

	//The logout method
	public function logout(){
		$this->updateisLoggedin(0);

		//Log the logout activity
		$this->saveLogoutActivity();

		//Update Logged in time
		$this->updateLoggedInfo();

		Auth::logout();
		Session::flush();
		return Redirect::route('home');
	}

	private function saveLogoutActivity(){
		//Log the logout activity
		doUserActivity::saveActivity('loggedout');
	}

	private function updateLoggedInfo(){
		doUserActivity::saveActivity('updateloggedTime', 0);
	}

}