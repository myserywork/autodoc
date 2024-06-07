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

    echo $this->getPesquisaHref($response);

    $pesquisa_infraToken = $this->getPesquisaHref($response);
    $pesquisa = $this->simplebrowser->get('https://sei.agro.gov.br/sei/'. $this->getPesquisaHref($response));
    echo($pesquisa);

    

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
}
