<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller {

	public function index()
	{
		$this->data['css_to_load'] = array();
		$this->data['js_to_load'] = array();
		$this->data['title'] = 'Tela de Início';
		$this->data['view'] = 'main/main_page';
		$this->_render_view('theme/model');
	}
}
