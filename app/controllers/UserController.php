<?php

class UserController extends BaseController {


	public $restful = true;

	public function get_new() {
		return View::make('user.new', array('title' => 'Sign up to property crunch'));
	}	

	public function get_show() {
		return View::make('user.show', array('title' => 'Customer Details'));
	}	

}