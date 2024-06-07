<?php

require_once("beta/autoload.php");

use Goutte\Client;


if(!defined('BASEPATH')) exit('No direct script access allowed');


class Automacoes extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
      
   public function index() {
    $client = new \Goutte\Client();
        // Create and use a guzzle client instance that will time out after 90 seconds
        $guzzleClient = new \GuzzleHttp\Client(array(
        'timeout' => 90,
        'verify' => false,
        ));



        $client->setClient($guzzleClient);
        $crawler = $client->request('GET', 'https://sei.agro.gov.br/sip/login.php?sigla_orgao_sistema=MAPA&sigla_sistema=SEI');







        $form = $crawler->selectButton('Entrar')->form();
        $crawler = $client->submit($form, array('txtUsuario' => 'pedro.noronha', 'pwdSenha' => 'Coopera2024!@','selOrgao' => 'MAPA'));

        print_r($crawler->html());
   }
}
