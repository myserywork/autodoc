<?php

require_once("beta/autoload.php");

use Goutte\Client;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Automacoes extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('SimpleBrowser');
        $this->load->helper(['Sei_helper', 'general_helper']);
        $this->load->model(['documentos_model', 'convenios_model']);
    }

    public function index() {
        $documento = $this->documentos_model->getDocumento(12);
        $convenio = $this->convenios_model->getConvenio($documento->id_convenio);

        $this->setHeaders();
        $token = $this->login();

        $pesquisa_url = $this->pesquisarProcesso($response['resultado']);
        $pesquisa_infraTokenValue = $this->getInfraHash($pesquisa_url);

        $pesquisaProcesso = $this->realizarPesquisaProcesso($pesquisa_infraTokenValue);
        $processo = $this->obterProcesso($pesquisaProcesso['redirectUrl']);
        $documento_url = $this->getIframeSrc($processo);
        $processo_visualizar = $this->visualizarProcesso($documento_url);

        $arvore_vizualisar = $this->obterArvoreVisualizacao($processo_visualizar);
        $lista = json_decode(getOptionsAsJson($arvore_vizualisar));
        $termo = array_search($documento->tipo, array_column($lista, 'text'));
        $criar_termo = $this->criarTermo($lista[$termo]->href);

        $url_form = $this->getFormUrl($criar_termo);
        $form = $this->simplebrowser->get($url_form);
        $token = getToken($form);

        $formPostData = $this->prepareFormPostData($documento, $convenio, $token, $url_form);

        $editor_montar_url = $this->getEditorMontarUrl($formPost['resultado']);
        $editor = $this->simplebrowser->get($editor_montar_url);
        $matches = $this->getEditorMatches($editor);

        $editor_salvar = $this->getEditorSalvarUrl($editor);
        $formPostData = $this->prepareEditorFormPostData($documento, $convenio, $matches, $editor_salvar);

        $this->submitEditorForm($editor_salvar, $formPostData);
    }

    private function setHeaders() {
        $this->simplebrowser->setHeaders([
            'Accept-Language: en-US,en;q=0.9',
            'Accept-Encoding: gzip, deflate, br',
        ]);
    }

    private function login() {
        $response = $this->simplebrowser->get('https://sei.agro.gov.br/sip/login.php?sigla_orgao_sistema=MAPA&sigla_sistema=SEI');
        $token = getToken($response);
        $this->simplebrowser->post('https://sei.agro.gov.br/sip/login.php?sigla_orgao_sistema=MAPA&sigla_sistema=SEI', [
            'txtUsuario' => 'pedro.noronha',
            'pwdSenha' => 'Coopera2024!@',
            'sbmLogin' => 'Acessar',
            'selOrgao' => 0,
            $token['name'] => $token['value'],
        ]);
        return $token;
    }

    private function pesquisarProcesso($resultado) {
        $pesquisa = $this->simplebrowser->get('https://sei.agro.gov.br/sei/' . $this->getPesquisaHref($resultado));
        return $this->getFormAction($pesquisa);
    }

    private function realizarPesquisaProcesso($pesquisa_infraTokenValue) {
        return $this->simplebrowser->post('https://sei.agro.gov.br/sei/controlador.php?acao=protocolo_pesquisar&acao_origem=protocolo_pesquisar&infra_sistema=100000100&infra_unidade_atual=120001411&infra_hash=' . $pesquisa_infraTokenValue, [
            'hdnInfraTipoPagina' => 1,
            'sbmPesquisar' => 'Pesquisar',
            'rdoPesquisarEm' => 'P',
            'txtProtocoloPesquisa' => '21000.032483/2024-67',
            'infra_hash' => $pesquisa_infraTokenValue
        ], false);
    }

    private function obterProcesso($redirectUrl) {
        return $this->simplebrowser->get($redirectUrl);
    }

    private function visualizarProcesso($documento_url) {
        return $this->simplebrowser->get('https://sei.agro.gov.br/sei/' . $documento_url);
    }

    private function obterArvoreVisualizacao($processo_visualizar) {
        $parse = parse($processo_visualizar, "Nos[0].acoes = '", '" tabi');
        $parse = str_replace('<a href="', '', $parse);
        $parse = str_replace('"', '', $parse);
        return $this->simplebrowser->get('https://sei.agro.gov.br/sei/' . $parse);
    }

    private function criarTermo($href) {
        return $this->simplebrowser->get('https://sei.agro.gov.br/sei/' . $href);
    }

    private function getFormUrl($criar_termo) {
        $url_form = parse($criar_termo, '<form id="frmDocumentoCadastro" method="post" onsubmit="return OnSubmitForm();" action="', '"');
        return 'https://sei.agro.gov.br/sei/' . $url_form;
    }

    private function prepareFormPostData($documento, $convenio, $token, $url_form) {
        return [
            'hdnInfraTipoPagina' => 2,
            'txtDataElaboracao' => '07/06/2024',
            'txtNumero' => rand(1555, 55550),
            'hdnIdSerie' => 1634,
            'hdnIdUnidadeGeradoraProtocolo' => 120001411,
            'hdnStaDocumento' => 'I',
            'hdnIdProcedimento' => 48844413,
            'hdnIdTipoProcedimento' => 100000305,
            $token['name'] => $token['value'],
        ];
    }

    private function getEditorMontarUrl($resultado) {
        return parse($resultado, "janelaEditor.location.href = '", "'");
    }

    private function getEditorMatches($editor) {
        preg_match_all('/txaEditor_\d{1,10}/', $editor, $matches);
        return $matches;
    }

    private function getEditorSalvarUrl($editor) {
        $editor_salvar = parse($editor, 'target="ifrEditorSalvar" action="', '"');
        return 'https://sei.agro.gov.br/sei/' . $editor_salvar;
    }

    private function prepareEditorFormPostData($documento, $convenio, $matches, $editor_salvar) {
        $documento->texto = $this->substituirValoresTagsPorKeys($documento->texto, $convenio);
        $conteudo_principal = mb_convert_encoding($documento->texto, 'HTML-ENTITIES', 'UTF-8');

        $formPostData = [
            'hdnVersao' => 1,
            'hdnIgnorarNovaVersao' => 'N',
            'hdnSiglaUnidade' => 'DAPI-SDI',
            'hdnInfraPrefixoCookie' => 'MAPA_SEI_pedro.noronha'
        ];

        if (isset($matches) && is_array($matches)) {
            foreach ($matches[0] as $match) {
                $formPostData[$match] = '';
            }

            $formPostData[$matches[0][0]] = $conteudo_principal;
        } else {
            echo "Erro: \$matches não está definido ou não é um array.";
        }

        return $formPostData;
    }

    private function submitEditorForm($editor_salvar, $formPostData) {
        $formPost = $this->simplebrowser->post($editor_salvar, $formPostData);
        echo $formPost['resultado'];
        die();
    }

    function substituirValoresTagsPorKeys($texto, $convenio) {
        $convenio = (array) $convenio;
        foreach (array_keys($convenio) as $tag) {
            $texto = str_replace("%[$tag]%", $convenio[$tag], $texto);
        }
        return $texto;
    }

    function getPesquisaHref($html) {
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        $ul = $xpath->query('//ul[@id="main-menu"]')->item(0);
        if ($ul) {
            $links = $xpath->query('.//li/a', $ul);
            foreach ($links as $link) {
                if (trim($link->nodeValue) === 'Pesquisa') {
                    return $link->getAttribute('href');
                }
            }
        }
        return null;
    }

    function getInfraHash($url) {
        $parsedUrl = parse_url($url);
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
            return $queryParams['infra_hash'] ?? null;
        }
        return null;
    }

    function getFormAction($html) {
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        $form = $xpath->query('//form[@id="frmPesquisaProtocolo"]')->item(0);
        return $form ? $form->getAttribute('action') : null;
    }

    function getIframeSrc($html) {
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $iframe = $dom->getElementById('ifrArvore');
        return $iframe ? $iframe->getAttribute('src') : null;
    }
}
