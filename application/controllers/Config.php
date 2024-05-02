<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
    }

	public function index()
	{

		$this->data['css_to_load'] = array();
		$this->data['js_to_load'] = array();
		$this->data['title'] = 'Configurações';
		$this->data['view'] = 'config/init';
		$this->_render_view('theme/model');
	}
}
