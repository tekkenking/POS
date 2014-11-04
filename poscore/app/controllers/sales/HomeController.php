<?php

class HomeController extends \SalesBaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function show()
	{
		//Artisan::call('db:dump');
		//tt( Receipt::find(2)->user()->get()->toArray() );
		Larasset::start('footer')->js('jquery-cookies', 'bootstrap_editable');
		
		$this->layout->title = Systemsetting::getx('name') . ' - Home';
		$this->layout->content = View::make('frontpage');
	}

}