<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Documentos extends MY_Controller
{

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
        $this->data['css_to_load'] = array();
        $this->data['js_to_load'] = array();
        $this->data['title'] = 'Documentos';
        $this->data['view'] = 'documentos/listar';
        $this->_render_view('theme/model');
    }

    public function modelo()
    {

        if ($this->uri->segment(4) && is_numeric($this->uri->segment(4))) {

            $documentId = $this->uri->segment(4);
            $this->data['documento'] = $this->documentos_model->getDocumento($documentId);

            if (!$this->data['documento']) {
                $this->session->set_flashdata('error', 'Documento não encontrado, por favor, tente novamente.');
                redirect(base_url() . 'documentos');
            }
        }

        if ($this->uri->segment(3) && is_numeric($this->uri->segment(3))) {
            $id = $this->uri->segment(3);
            $this->data['convenio'] = $this->convenios_model->getConvenio($id);
            $this->data['convenio_items'] = array_keys((array)$this->data['convenio']);

            $this->data['convenios'] = $this->convenios_model->getConvenios();


            $this->data['css_to_load'] = array();
            $this->data['js_to_load'] = array('js/documentos/editar.js');
            $this->data['title'] = 'Documentos';
            $this->data['view'] = 'documentos/editar';
            $this->_render_view('theme/model');
        } else {
            $this->session->set_flashdata('error', 'Convênio não encontrado, por favor, tente novamente.');
            redirect(base_url() . 'documentos');
        }
    }

    function substituirValoresTagsPorKeys($texto, $convenio)
    {
        $convenio = (array)$convenio;
        $tags = array_keys($convenio);

        foreach ($tags as $tag) {
            // echo 'Tag: %[' . $tag . ']% - Valor: ' . $convenio[$tag] . '<br/>';
            $texto = str_replace("%$tag%", $convenio[$tag], $texto);
        }

        return $texto;
    }

    public function gerar_pdf()
    {

        $this->load->library('dompdf_lib');

        if ($this->uri->segment(3) && is_numeric($this->uri->segment(3))) {
            $id = $this->uri->segment(3);

            $documento = $this->documentos_model->getDocumento($id);

            if(!$documento) {
                $this->session->set_flashdata('error', 'Documento não encontrado, por favor, tente novamente.');
                redirect(base_url() . 'documentos');
            }

            $convenio = $this->convenios_model->getConvenio($documento->id_convenio);

            $documento->texto = $this->substituirValoresTagsPorKeys($documento->texto, $convenio);

            // Carregar a view que deseja transformar em PDF
            $html = $documento->texto;

            // Carregar HTML no DomPDF
            $this->dompdf_lib->load_html($html);
            

            // Renderizar PDF
            $this->dompdf_lib->render();

            // Gerar PDF e exibir ou fazer download
            $this->dompdf_lib->stream($documento->nome . " - CONVÊNIO_" . $convenio->NR_CONVENIO . ".pdf");
        } else {
            $this->session->set_flashdata('error', 'Documento não encontrado, por favor, tente novamente.');
            redirect(base_url() . 'documentos');
        }
    }

    public function salvar_modelo()
    {

        $this->load->library('form_validation');

        $post = $this->input->post();

        $this->form_validation->set_rules('idConvenio', 'idConvenio', 'required');
        $this->form_validation->set_rules('nome', 'nome', 'required');
        $this->form_validation->set_rules('tipo', 'tipo', 'required');
        $this->form_validation->set_rules('text', 'text', 'required');
        $this->form_validation->set_rules('idDocumento', 'idDocumento');

        if ($this->form_validation->run() === TRUE) {
            $data = array(
                'id_convenio' => $post['idConvenio'],
                'nome' => $post['nome'],
                'tipo' => $post['tipo'],
                'texto' => $post['text'],
                'atualizado_em' => date('d/m/Y H:i:s')
            );

            $this->data['convenio'] = $this->convenios_model->getConvenio($post['idConvenio']);

            if (!$this->data['convenio']) {
                $this->returnJson(
                    array('success' => false, 'msg' => 'Convênio não encontrado, por favor, tente novamente.'),
                    400
                );
                return;
            }

            if (isset($post['idDocumento']) && is_numeric($post['idDocumento'])) {
                try {
                    $add = $this->documentos_model->edit('documentos', $data, 'id', $post['idDocumento']);
                } catch (Exception $e) {
                    $this->returnJson(
                        array('success' => false, 'msg' => '[ID: 4] Erro ao salvar o modelo, por favor, tente novamente. Info: ' . $e->getMessage()),
                        400
                    );
                    return;
                }
                $this->returnJson(
                    array('success' => true, 'msg' => 'Modelo atualizado com sucesso.'),
                    200
                );
            } else {
                $count = $this->documentos_model->countDocumentosPorConvenioENome($post['idConvenio'], $post['nome']);

                if ($count > 0) {
                    $this->returnJson(
                        array('success' => false, 'msg' => 'Já existe um modelo com este nome para este convênio.'),
                        400
                    );
                    return;
                }

                try {
                    $data = array(
                        'id_convenio' => $post['idConvenio'],
                        'nome' => $post['nome'],
                        'tipo' => $post['tipo'],
                        'texto' => $post['text'],
                        'atualizado_em' => date('d/m/Y H:i:s'),
                        'criado_em' => date('d/m/Y H:i:s')
                    );
                    $add = $this->documentos_model->add('documentos', $data);
                } catch (Exception $e) {
                    $this->returnJson(
                        array('success' => false, 'msg' => '[ID: 1] Erro ao salvar o modelo, por favor, tente novamente. Info: ' . $e->getMessage()),
                        400
                    );
                    return;
                }

                if ($add) {
                    $insert_id = $this->db->insert_id();

                    $this->returnJson(
                        array('success' => true, 'msg' => 'Modelo salvo com sucesso.', 'documentId' => $insert_id),
                        200
                    );
                    return;
                } else {
                    $this->returnJson(
                        array('success' => false, 'msg' => '[ID: 2] Erro ao salvar o modelo, por favor, tente novamente.'),
                        400
                    );
                    return;
                }
            }
        } else {
            $this->returnJson(
                array('success' => false, 'msg' => '[ID: 3] Erro ao salvar o modelo, por favor, tente novamente.'),
                400
            );
            return;
        }
    }

    public function upload_img()
    {
        $response = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
            $has_image = $_FILES['image']['name'];

            $config['upload_path'] = './assets/user_uploads/';
            $config['allowed_types'] = 'jpg|png|jpeg';
            $config['max_size'] = 2048000;
            //$config['max_width'] = 1024;
            //$config['max_height'] = 768;
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);

            if (!empty($has_image)) {
                if (!$this->upload->do_upload('image')) {
                    $response['status'] = 'error';
                    $response['error'] = 'Falha ao fazer upload da imagem. Retorno: '.$this->upload->display_errors();
                } else {
                    $image_data = array('upload_data' => $this->upload->data());
                    $response['status'] = 'success';
                    $response['url'] = base_url().'/assets/user_uploads/'. $image_data['upload_data']['file_name'];
                }
            }
        } else {
            $response['status'] = 'error';
            $response['error'] = 'Nenhum arquivo enviado.';
        }

        echo json_encode($response);
    }

    function getDocumentos() {
		$documentos = $this->documentos_model->getTop100Documentos();
		echo json_encode($documentos);
	}
}
