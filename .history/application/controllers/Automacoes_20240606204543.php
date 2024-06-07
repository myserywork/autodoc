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
$crawler = $client->request('GET', 'https://www.facebook.com/');



$form = $crawler->selectButton('Entrar')->form();
$crawler = $client->submit($form, array('email' => 'caioferreirasts@gmail.com', 'pass' => 'CaioDark010430'));

print_r($crawler->html());
   }
}
