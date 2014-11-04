<?php namespace libraries;

use Illuminate\Support\ServiceProvider;

class libServiceProvider extends ServiceProvider{

	public function register(){
		// Register 'coded' instance container to our coded object
		$this->app['coded'] = $this->app->share(function($app){
			return new \Libraries\Coded;
		});
	}

}