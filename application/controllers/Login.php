<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
    
	public function index()
	{
        /* Caso o usuário já tenha feito login */
        if ($this->ion_auth->logged_in()) {
            redirect('', 'refresh');
        }

        $this->load->view('login/login');
	}
}
