<?php

class GraphSearchController extends \SecureBaseController
{
	public function index()
	{
		$this->layout->title = 'G56 - Graph search';
		$this->layout->content = View::make('graphsearchpage');
	}

}