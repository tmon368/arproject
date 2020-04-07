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
		
	    echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}

	public function get_promotions_of_tourists()
	{
		$this->load->model('ApiModel');
		$touris_id = $this->input->get('touris_id');
		$data = $this->ApiModel->get_pro_of_tourID($touris_id);
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}

	public function get_promotions_of_area()
	{
		$this->load->model('ApiModel');
		$lat = $this->input->get('lat');
		$long = $this->input->get('long');
	
		$data = $this->ApiModel->get_pro_of_area($lat,$long);
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	public function get_informations()
	{
		$this->load->model('ApiModel');
		$data = $this->ApiModel->get_info_all();
		
	    echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}

	public function tourists_checkIn()
	{
		$this->load->model('ApiModel');
		$touris_id = $this->input->get('touris_id');
		$info_id = $this->input->get('info_id');
		
		$promotion = $this->input->get('promotion');
	}

	public function register_facebook()
	{
		$this->load->model('ApiModel');
		//$email = $this->uri->segment(3);
		//$name = $this->uri->segment(4);
		$email = strip_tags($this->input->get('email'));
		$name = $this->input->get('name');
		//echo $email,$name;
		$create_new_user = $this->ApiModel->register_fb($email,$name);
		echo json_encode($create_new_user,JSON_UNESCAPED_UNICODE);
	}

	public function login_facebook()
	{
		$this->load->model('ApiModel');
		$email = $this->input->get('email');
		$data = $this->ApiModel->login_fb($email);
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
}
