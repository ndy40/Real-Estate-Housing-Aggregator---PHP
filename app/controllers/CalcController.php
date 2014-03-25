<?php

class CalcController extends BaseController {


	public $restful = true;

	public function get_index() {
		return View::make('calculations.index', array('title' => 'Working on this page','pageTitle' => 'Coming Soon'));
	}	

	public function get_new() {
		return View::make('calculations.new', array('title' => 'Add a new calculation','pageTitle' => 'New Calculation'));
	}	

}