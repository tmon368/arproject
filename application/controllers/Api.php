<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	public function index()
	{
		//$this->load->view('welcome_message');
	}
	public function get_promotions_all(){
		$this->load->model('ApiModel');
	    $data = $this->ApiModel->get_pro_all();
	    echo json_encode($data);
	}
}
