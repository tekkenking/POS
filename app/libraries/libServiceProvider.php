<?php namespace libraries;

use Illuminate\Support\ServiceProvider;

class libServiceProvider extends ServiceProvider{

	public function register(){

		$this->app['coded'] = $this->app->share(function($app){
			return new \Libraries\Coded;
		});	

		$this->app['cartsession'] = $this->app->share(function($app){
			return new \App\Libraries\cartSession;
		});
	}

}