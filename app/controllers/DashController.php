<?php

class DashController extends BaseController {


	public $restful = true;

	public function get_index() {
		return View::make('dashboard.index', array('title' => 'This is your dashboard','pageTitle' => 'Dashboard'));
	}	

}