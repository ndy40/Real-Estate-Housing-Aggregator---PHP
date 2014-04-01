<?php

class DashboardController extends BaseController {


	public $restful = true;

	public function get_index() {
		return View::make('dashboard.index', array('title' => 'Welcome to Property Crunch'));
	}	


}