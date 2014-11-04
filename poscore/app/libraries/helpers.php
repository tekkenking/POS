<?php

/*
* A HELPER LOADER CLASS
*/
class Helpers
{
	public static function loader()
	{
		$path = app_path() . '/helpers/';

		foreach (glob($path . "helper_*.php") as $filename) {
			require_once($filename);
		}
	}
}