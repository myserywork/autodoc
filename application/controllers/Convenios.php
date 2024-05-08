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
		$this->data['js_to_load'] = array(
			'js/convenios.js'
		);
		$this->data['title'] = 'Convênios';
		$this->data['view'] = 'convenios/listar';
		$this->_render_view('theme/model');
	}

	function getConvenios() {
		$page = $this->input->get('page') ?? 1; // Página atual (padrão: 1)
		$perPage = 20; // Quantidade de itens por página

		$nameOrNumberSearch = $this->preventSqlInjection($this->input->get('nameOrNumberSearch'));
		$searchStatus = $this->preventSqlInjection($this->input->get('searchStatus'));
		$searchEstado = $this->preventSqlInjection($this->input->get('searchEstado'));
		$searchDate = $this->preventSqlInjection($this->input->get('searchDate'));

		if($searchDate != '') {
			$searchDate = (strtotime($searchDate) + 79200) . '000';
		}

		$totalConvenios = $this->convenios_model->countConvenios($nameOrNumberSearch, $searchStatus, $searchEstado, $searchDate);
		$totalPages = ceil($totalConvenios / $perPage); // Total de páginas

		$offset = ($page - 1) * $perPage; // Offset para a consulta

		$convenios = $this->convenios_model->getConveniosPagination($perPage, $offset, $nameOrNumberSearch, $searchStatus, $searchEstado, $searchDate);

		foreach ($convenios as $convenio) {
			if($convenio->SIT_CONVENIO == null) {
				$convenio->SIT_CONVENIO = 'Não Informado';
			}
			$convenio->inicio = date('d/m/Y', substr($convenio->DIA_INIC_VIGENCIA_PROPOSTA, 0, -3));
			$convenio->fim = date('d/m/Y', substr($convenio->DIA_FIM_VIGENCIA_PROPOSTA, 0, -3));
		}

		$response = [
			'convenios' => $convenios,
			'currentPage' => $page,
			'totalPages' => $totalPages
		];

		echo json_encode($response);
	}
}
