<?php

require_once("beta/autoload.php");

use Goutte\Client;


if(!defined('BASEPATH')) exit('No direct script access allowed');


class Automacoes extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->library('SimpleBrowser');
        $this->load->helper('Sei_helper');
        $this->load->helper('general_helper');
    }
    
      
    public function index() {

        $this->simplebrowser->setHeaders([
            'Accept-Language: en-US,en;q=0.9',
            'Accept-Encoding: gzip, deflate, br',
        ]);

        // Realizando uma requisição GET
        $response = $this->simplebrowser->get('https://sei.agro.gov.br/sip/login.php?sigla_orgao_sistema=MAPA&sigla_sistema=SEI');
        $token = getToken($response);

        // Realizando uma requisição POST

        $response = $this->simplebrowser->post('https://sei.agro.gov.br/sip/login.php?sigla_orgao_sistema=MAPA&sigla_sistema=SEI', [
            'txtUsuario' => 'pedro.noronha',
            'pwdSenha' => 'Coopera2024!@',
            'sbmLogin' => 'Acessar',
            'selOrgao' => 0,
            $token['name'] => $token['value'],
        ]);

        //echo($response);

        //print_r($response);

        $pesquisa = $this->simplebrowser->get('https://sei.agro.gov.br/sei/'. $this->getPesquisaHref($response['resultado']));    
        $pesquisa_url = $this->getFormAction($pesquisa);
        $pesquisa_infraTokenValue = $this->getInfraHash($pesquisa_url);
        
        //echo($pesquisa);

        //var_dump($pesquisa_infraTokenValue);

        $pesquisaProcesso = $this->simplebrowser->post('https://sei.agro.gov.br/sei/controlador.php?acao=protocolo_pesquisar&acao_origem=protocolo_pesquisar&infra_sistema=100000100&infra_unidade_atual=120001411&infra_hash='. $pesquisa_infraTokenValue, [
       
            'hdnInfraTipoPagina' => 1,
            'sbmPesquisar' => 'Pesquisar',
            'rdoPesquisarEm' => 'P',
            'q' => '',
            'txtUnidade' => '',
            'hdnIdUnidade' => '',
            'txtAssunto' => '',
            'hdnIdAssunto' => '',
            'txtAssinante' => '',
            'hdnIdAssinante' => '',
            'txtContato' => '',
            'hdnIdContato' => '',
            'chkSinInteressado' => 'S',
            'txtDescricaoPesquisa' => '',
            'txtObservacaoPesquisa' => '',
            'txtProtocoloPesquisa' => '21000.032483/2024-67',
            'selTipoProcedimentoPesquisa' => '',
            'txtNumeroDocumentoPesquisa' => '',
            'txtDataInicio' => '',
            'txtDataFim' => '',
            'txtUsuarioGerador1' => '',
            'hdnIdUsuarioGerador1' => '',
            'txtUsuarioGerador2' => '',
            'hdnIdUsuarioGerador2' => '',
            'txtUsuarioGerador3' => '',
            'hdnIdUsuarioGerador3' => '',
            'hdnInicio' => 0,
            'infra_hash' => $pesquisa_infraTokenValue
     
        ], false);

        
       echo $pesquisaProcesso['redirectUrl'];

     

  



        $processo = $this->simplebrowser->get($pesquisaProcesso['redirectUrl']);

      //  echo $processo;


    

        $documento_url = $this->getIframeSrc($processo);

        //  echo $documento_url;

    

   

        $processo_visualizar = $this->simplebrowser->get('https://sei.agro.gov.br/sei/'. $documento_url);

       // echo ($processo_visualizar);

    
   
        $parse = parse($processo_visualizar,"Nos[0].acoes = '",'" tabi');
        $parse = str_replace('<a href="','',$parse);
        $parse = str_replace('"','',$parse);

     
      
        
      
        $arvore_vizualisar = $this->simplebrowser->get('https://sei.agro.gov.br/sei/'. $parse);




        $lista = json_decode(getOptionsAsJson($arvore_vizualisar));
      
        //function to search in the lista which object has tehe text  Termo de Convenio 

        $termo = array_search('Termo de Convênio', array_column($lista, 'text'));

        dump($lista[$termo]);

        $criar_termo = $this->simplebrowser->get('https://sei.agro.gov.br/sei/'. $lista[$termo]->href);

       // echo $criar_termo;

        $url_form = parse($criar_termo,'<form id="frmDocumentoCadastro" method="post" onsubmit="return OnSubmitForm();" action="','"');
        $url_form = 'https://sei.agro.gov.br/sei/'. $url_form;

        

        $form = $this->simplebrowser->get('https://sei.agro.gov.br/sei/'. $url_form);


        $token = getToken($form);


       $formPostData =  [
        'hdnInfraTipoPagina' => 2,
        'txtDataElaboracao' => '07/06/2024',
        'txtProtocoloDocumentoTextoBase' => '',
        'selTextoPadrao' => 'null',
        'rdoTextoInicial' => 'N',
        'hdnIdDocumentoTextoBase' => '',
        'txtNumero' => rand(1555,55550),
        'txtDescricao' => '',
        'txtRemetente' => '',
        'hdnIdRemetente' => '',
        'txtInteressado' => '',
        'hdnIdInteressado' => '',
        'txtDestinatario' => '',
        'hdnIdDestinatario' => '',
        'txtAssunto' => '',
        'hdnIdAssunto' => '',
        'txaObservacoes' => '',
        'selGrauSigilo' => 'null',
        'rdoNivelAcesso' => 0,
        'hdnFlagDocumentoCadastro' => 2,
        'hdnAssuntos' => '',
        'hdnInteressados' => '',
        'hdnDestinatarios' => '',
        'hdnIdSerie' => 1633,
        'hdnIdUnidadeGeradoraProtocolo' => 120001411,
        'hdnStaDocumento' => 'I',
        'hdnIdTipoConferencia' => '',
        'hdnStaNivelAcessoLocal' => '',
        'hdnIdHipoteseLegal' => '',
        'hdnStaGrauSigilo' => '',
        'hdnIdDocumento' => '',
        'hdnIdProcedimento' => 48844413,
        'hdnAnexos' => '',
        'hdnIdHipoteseLegalSugestao' => '',
        'hdnIdTipoProcedimento' => 100000305,
        'hdnUnidadesReabertura' => '',
        'hdnSinBloqueado' => 'N',
        'hdnContatoObject' => '',
        'hdnContatoIdentificador' => '',
        'hdnAssuntoIdentificador' => ''
   
    ];


    $formPost = $this->simplebrowser->post($url_form, $formPostData);

    print_r( $formPost );




    

        
        












        /*

        $documento = $this->simplebrowser->get('https://sei.agro.gov.br/sei/'. $documento_url);

        echo $documento;*/

       
   }

    function getPesquisaHref($html) {
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        // Seleciona o ul com id="main-menu"
        $ul = $xpath->query('//ul[@id="main-menu"]')->item(0);
        if ($ul) {
            // Seleciona todos os a tags dentro de li tags
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
            if (isset($queryParams['infra_hash'])) {
                return $queryParams['infra_hash'];
            }
        }
        return null;
    }

    function getFormAction($html) {
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        // Seleciona o form com id="frmPesquisaProtocolo"
        $form = $xpath->query('//form[@id="frmPesquisaProtocolo"]')->item(0);
        if ($form) {
            return $form->getAttribute('action');
        }

        return null;
    }

    function getIframeSrc($html) {
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $iframe = $dom->getElementById('ifrArvore');
        if ($iframe) {
            return $iframe->getAttribute('src');
        }

        return null;
    }
}
