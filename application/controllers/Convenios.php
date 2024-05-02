<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Convenios extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'convenios_model'
        ));
    }

	public function index()
	{

		$this->data['css_to_load'] = array();
		$this->data['js_to_load'] = array();
		$this->data['title'] = 'Convênios';
		$this->data['view'] = 'convenios/listar';
		$this->_render_view('theme/model');
	}

	function getConvenios() {
		$convenios = $this->convenios_model->getTop10Convenios();

		foreach ($convenios as $convenio) {
			$convenio->inicio = date('d/m/Y', substr($convenio->DIA_INIC_VIGENCIA_PROPOSTA, 0, -3));
			$convenio->fim = date('d/m/Y', substr($convenio->DIA_FIM_VIGENCIA_PROPOSTA, 0, -3));
		}

		echo json_encode($convenios);
	}
}
