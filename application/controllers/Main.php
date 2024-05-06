<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'documentos_model',
            'convenios_model'
        ));
    }

	public function index()
	{

		$this->data['countConvenios'] = $this->convenios_model->countAllConvenios();
		$where = "SIT_CONVENIO ='Prestação de Contas Concluída'";
		$this->data['conveniosAprovados'] = $this->convenios_model->countConveniosWithWhere($where);
		$where = 'SIT_CONVENIO IS NOT NULL AND SIT_CONVENIO <> Cancelado';
		$this->data['conveniosPendentes'] = $this->convenios_model->countConveniosWithWhere($where);
	
		$this->data['documentos'] = $this->documentos_model->getTop5Documentos();

		$this->data['css_to_load'] = array();
		$this->data['js_to_load'] = array();
		$this->data['title'] = 'Tela de Início';
		$this->data['view'] = 'main/main_page';
		$this->_render_view('theme/model');
	}
}
