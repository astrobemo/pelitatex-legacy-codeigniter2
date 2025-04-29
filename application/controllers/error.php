<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Error extends CI_Controller {

	function index(){
		//echo 'ERRORR UYYY';
	}

	function _404(){
		$this->load->view('error.html');
	}

	function maintenance_mode(){
		$this->load->view('maintenance.html');
	}

}