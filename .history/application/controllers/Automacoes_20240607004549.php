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
        'hdnIdSerie' => 1634,
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

    $editor_montar_url = parse($formPost['resultado'],"janelaEditor.location.href = '","'");


    
    $editor = $this->simplebrowser->get('https://sei.agro.gov.br/sei/'. $editor_montar_url);
   
    /* target="ifrEditorSalvar" action=" */
    $editor_salvar = parse($editor,'target="ifrEditorSalvar" action="','"');

    $editor_salvar = 'https://sei.agro.gov.br/sei/'. $editor_salvar;

    $this->load->model(array(
        'documentos_model',
        'convenios_model'
    ));


    
    $documento = $this->documentos_model->getDocumento(12);

    $convenio = $this->convenios_model->getConvenio($documento->id_convenio);

    $documento->texto = $this->substituirValoresTagsPorKeys($documento->texto, $convenio);



    $formPostData =  [
        'txaEditor_6190'=> urldecode('%3Cp+class%3D%22Cabe%E7alho%22%3E%3Cimg+alt%3D%22Timbre%22+src%3D%22data%3Aimage%2Fpng%3Bbase64%2CiVBORw0KGgoAAAANSUhEUgAAAFcAAABWCAIAAAA19p2eAAAABnRSTlMA%2FwD%2FAP83WBt9AAAACXBIWXMAAC4jAAAuIwF4pT92AAAgAElEQVR4nO18Z5gVVdburlx1cg7dp3Nuco6iSFIQFIw4ihnToGNGHXMcZ8QwjoOOYcSIIEgQFFCC5NB003TuPp27T86Vw74%2FdBS1Acfhm%2Fs9z73r36mnaq93vWftvddea1UhEELw%2F7yg%2F7cB%2FK%2BQ%2FzYLzUdXJ2KBU9zQ07qnv7P6v4bnO%2FmvspBMJmDXA721r9RVbQHg5zNRUZSaQ5u55j8k299m2cx%2FE9h%2FjwVB4A9vWITqy0nYkz5%2BbU3VXkkSAQCKogAAouHuo7v%2BzrCrEdEfiBNt%2Bx78rwEDZ5wFWZZPstzC5uN7DdrBjJILhTabwxdp%2F7xlz33t%2FqYDW26KRsMtX05R%2BY5YhrEZ0oqsMlisrnrHgCpURTmzmMEZZ6Gt6s2ja8%2Bpr94a7G088Xp7Sy3at9TqGRZNAiD4ESoHaAoJe%2Bt2LsyzfnR4xzJZCJv1YobVJAWxGqRQxis03qxp6k8GadxetX9L%2FdrCvp7WMwsbP7PDxVhrhXnfUf83avv6FuLSrNwyjR5sMur6W9ZQ6U6RHI9A3kjFenkTgEoy2ppn7zn%2BqNVx95cqZ8JhFABjWs420h1xttzpHPrNqjsnzf1TLBqMt77el8zxKi9jhrP1OiTD8mcW9plkIRGPIYGXe60XZFHfaMAjSSrf9iQwjDsWN3upXYRrbGcMuC28JOOJjIbhIk21Zj5nCjsJrrUtZigyckEA6X5uGBKpcedkR%2Fji7pbPWnaGEnKFTf7Uazovw19QgLzbotxKHb8XlH11BpGfyRkRDrSZrI6oUGDDj%2FWygz30IQQ39qd9XkPj6oPca99GjTpRzrSKaBGAKpA7FA5h9jAHcch9ZHB42C%2F3YSqc1sGeZ7L5EPbo%2FmPyoMFPNbRbult2Cbp5cnQrjqbTco6ZaNWYUZFQ3xlEfiZZkHtejimjLOr6HvnCbP0RkYuGlcmEVL1oXdeLffQXAb6xtfbjXXEJLQZQdpr2sJ%2Fpqkx4y21AF0dBddBuTY9haB2JH2gtY9mM1UKVYKQ794JCRzMvAIk5Jwtb2y3MQsQOBumK9Ww7g8jPGAupZDyqjLLLH0j02WnBbEKORaQRmtB6y5etdV4rHpdCELlrfzpslYMxDFeblChqqaFeHWtzFyvSbEFeZSjObVnf9OJHWzc8d%2BxIKzfGqa2%2BbuPbH3z%2Bot4x6vDxdFNrNMCWv7%2BnNQzPJ8QDDR1qR1fHmQL%2FW9aFQE%2BdIrEcx%2Bv1epZNG42WdCqWTkVs4jscPeP9XfWoGrhi4lyPoeXpTalOu5Hs5aUcPUQBl6Mbjnk1Tc1yNEVfNdfaiJoyIwAw%2F5ZUz5curEoT9LFnb3i25tD%2Bx3asn15YNMFWXYOSX%2BwvXmSviJUPWr9n08eGw7bDHy%2BYfOOxg4%2B8WDvxg0VPCIka0jxETtbgpuFK8qi3ZK7BaPp3LfotvpDo2UK1TY107UpVL4j2HkhWXRDrr6LDT6SRMa%2BtP%2FoOy%2FdLOgjlZKR%2Ba0rG47JqwoluVjPgpS1hn90G5BaxE7f5ib%2BOs0EEAABcPkW7iAcbdMNLAwfrjhUOGWGLoR929i5LZ2%2BKWXIsrXsdzl2NDUPc3wANLpecG7Z%2BBJSxB9DeLesfUPtfD9f9he35KF7%2FR2P4Vp7nfoNFv4UFlRrWwc0twl%2BIEtea2Dej5PUm%2Fr00Pn1TTe0qUgMqFBTfc7sbL%2F3KK1kIRINA0bjxDroh1cHon93brTN0sh8btjipxiLDv4aEBTelAYKgO3Ejte744aU3T5vzxoSb1K4U76Je7YP%2B6qX7ej6wZY11BjksJv4V6N7obMIS0p%2FrWptDhRRsl1RdFvVNm7pU07T%2FEguYWEtjET87t4h4NYZfZhLXxuBZSqb%2BZQ6DBApJdBse2p7BJEEiIqJGIHhCITpZ2aefiKEPjk7pulFrAH9tvPU7R%2FhO7G4ZuZQjv9blGQ4ihN1rUjKJxMJCba6EdeLaC4pvT5%2BAKgGflVBMBFBgzKlTdVgvpXuq5lha9vqYbW3ynVZhOYoiJwd%2BRlmA9GAWFhuwTn%2FmgkLyrRgyQw%2Br1zeaVF6lWzMAAkgggECxjAJUCEnMjmlnG8FzHurusY7CgozwiX6dl%2Fbn6382bPH1aYmG0kamJN9%2FvH1zWthb6nDq%2BMSQaEYLC93jnJ%2FUyAruVqwkQACAQDUSKoM1cRBJbW8Tb7aI7yf1d6iqOiDmM88CItZb0KoUMsGANralzyugPkqqQ7vFY3hCFgsMGK8SYQnlFLbSJObqfIT4yjjn3UNzS7LdohxAjiSMSezv422%2FPGyYbQp%2BZcZ4gNbLYVU%2F4fIZDxzsEwYZLTcNtb841HJdb%2FzTZNrPBvC0AlAEyyiAQAGEEEebEjMtyvokvcjCvoRhv8Wi38QCU5kA51jVdWl8JirVP7QxVwp%2FFVVKsYwCECjbSXaoGRE1PKNgvLrIaLYaVVmWVSma42rV1uhX5zDdPmbAkUuvybAmjVulnzi0prWtrcxUdFZxz7pO5YsA9CGePzCcISaKuTo8IWl6nGpn8ZQsOclezt8Qn0mmVyUMd6vqf2VdgBCqfJdR2vDS%2Fkq2592lu2zrRVl4UUntr1dMOBGR8IRsPBDjKkwaAvCw%2BEafFg83SpKgYryyiyIF9I3xtpMNbjCq1KKMvZ7UhQ4eaHh1RFGwL2HvUzObaHlZX%2BBJwITdOrKPl20kERNFH4NKEAHgzZi0omH98oYsY%2BZNNpP%2BDSxgjz%2F%2B%2BK%2B8tautKtD2Rc2hLXRy%2BWdNw5dzXV%2FW0T24oAeIuYVdxIPdqpbOYiCBQBKleniAIZqXuUyHFTq8BBLLdTTCN00r8%2FRfjbQCABAIcE7WCfIEMpmjFxEEoSgAADCXKx1rdVIzkTUno0OACB0TnUxtCwg7ECIkiAUGpjEFUJRpyygOSrZTZEDM6LGuNNEJpHm545XuF9qDFj6wAVIler0BgF%2B1WCKnzr5qmtrZVptKRdWOh3jqPDN6BFV725Izbvt6UxLBNQbD4xJiJhbtDDg4kIdhLw4mAhYjpDEb0IabiPM9FpcFChJGUy3WfSlyF7PwQseEoZmpWckSmgeA5jGzohEUKlBy3GaQCAICADo3Mtlv6zO%2FTzapxTqdgySZQKDzsTASwQiyT0A0KNtJLK2gnCLm6xFJgziCSprK4DdRzFXjhxjEz%2BPkNWa8MRDmfSMe0jFMbtHI%2F4iFULBXOlLCaQW98lwncTCRUhXdpDXVG99UdGRAkB0kKkAxh3m4Uxds76g1IxNybFcMj4dSbojqMEIv8gkEwXg2UpZVH%2F6n0bmISxSYN3TP3No3uVGoVPQOCBCMT6mMCdVUhusZZamZ7dsxzbUL3a31baWt16uR6HAApVAkzCmeDUm%2BOiEKBgRPyKoOQzSgkQgRFmUXjagQQIiZ8M%2BG5%2BrNPh2%2FLk1dZCDCPuKzPvnCIeev%2FI9YAAB8u%2B0jm7rOhawLCiN%2F9xU5z970Xq%2Bnb5CJbs8gvApQBCAgT5D8Lr1qxBf2R4c6vJKSqMyzQoAAlFZkQdQCg4d3%2BGHxszW3HIbTIYpXyt1hEYkDegjN3VBCvNAAgxI2Vp%2FeLzlkxoKLyfm21fcMeofMpI7sLzbp6H29huUMb4lwLE3CjIwKKkAQLCljrCIU6DUG44sNpgNRycM8RIdnjJkMECKPWpWWvQFtfs7ga%2FMLK05t4%2BlXR6cpBbnaBu7325sMTU7plX5P2EUyLWmUUzU9LvkYjcH8Th3EESwpr1F0D6HSGxwlcFFRTujoVgG2lI4LLK1%2FcEH1Z3G2xMCFjdG2lybbq68pNRDoOT761vMn6BF5ikPd%2Fvtpd7lDhlSfTuZ2R2ZP2b3p48j8IZO6NSI6ztdaoSTlqAQFha80qQZCtpGyi1L1OBEVUVEDkiY5KABhiCr1El9Z0X0NmRsEbJiO%2FcRqdZ3WxtP7gigI29b9Kd%2FWdO%2BWo%2Fu7sMwoK%2B3PqDSm2im6LSO5aVTWqG7OY1Erc%2BQhDq7cJNg1xSUgSieWKFLEct81%2B%2F8aJgv1UvLzaUYbJo35Gluo73aZjctbVDehAARENTIX4y4rNb3flNEAevz2MU9sqnmhxwoxYgz46h%2BT%2FhjcpLohULPUEAqDgKzWTA3tRBVjZUOa7KaZ5oxKIpDEZA91FicuHTLKbejUxABLzSFNg8dMWXhaFk5%2FpqRo2kMfBpnmRtkm5GuQQIiQSDvwMjw1tISrzJXzKME5VNXHUMmPS%2FtJtVNnl1EMgPAcLlJYtmj%2FmyUwMYzo3RMRNzfzehI3ssI%2BoX%2BWYe2rY%2Bt8uiCJShlV70%2Fl7A8NiylTsgjy86q2rztSZWrGbWIa00Pm7XznsxmLuUdQRzuOA6A3aGWFPFmYJL3BqE%2BNMVRDvq7tgHqEtrdw6JGIygB%2FkB%2FmMzN48m19%2Ft9Oa%2BCv8gUAwPHqXc21mz6u%2B6QsR6p0SU5JdAsAdmOin5D8BBVCzQCRAYhRGl6gkkUyWaz04agy2HvxgQ8l0vRMQfyeGUMmv767WjRPNO64f8g%2FJEh%2B0TX1QHRkF%2BcVNcKIccWmzrPch2Znfd2SLnjh8DVtukl%2FzI0%2BPn%2FilNe%2F3Q19BUr16vE3PvRPryGFlsTkkqhUlJLdEEEAjGAa4lOJQoUqlBSPFqYQApo8hlhP6qyUPPLcuffa7I4zwEKHv47vOEft5aUqUvQTSgdu5VEMgCihoXkqWaSQRTJVrBiKZI9PwzAVABAIkIGkccb2lVEi3x5vs%2BjIBW7x7S75hTHPUbjyaPXd7eTom4j6d7lCmWAQAOG%2FdnVElc4mNz4%2B8rU1bVP%2F2XbVLQXqu706RJXDBt9Z%2BIb7hj590aHBKo4CAFAFmmJiUVQuiUklUak4JhWlFQcALICsVSMLFapQNp8j9PEjRkzfptPp%2FlMWWJb98v0LSxz7hOcsWgrdN0I795KUtULy5qkErv2yxKRpSGsbfevex3ays96pjFtd3qt2sgat5%2BOz7%2Fhr7cLPuBsgguFipvlSW2uMz3Dc4u3RiDn%2FxBFIKfVU0R%2FtdPK2Yy%2BVkPzqC3x%2F3d7wd6HsuZy7GpMN%2F0xmD2AGAKikWWNSeS93QVtiXhBGKyXyWo0jH5k04xaCIE9t42n2iEO7PvR%2FNWpwWW6vOJt5IIHpNblfN6up%2FPHq3K9qjLwwwOPtHVRVrHK7cjGiqWkVzQiSQer75Jw7Hjx072p%2BMUSwkkxL1UXmglzfjOElIQH8jAIAgESalna9XBfNf2XIvSkJRlMcrwIIkKdbH7ilIqYT5V8qJXllmiH69JT2V65qnyqqsSKZX8T%2Fo2aUR%2FvntyuGdXeepn5xUl%2BIxyL9VfdjONYXM0NNzrJGOjuOeOke5XnLZjvz2DyvRqAWTpjtiS3Ij8ysTBn0KgAAQqS1jZr%2Fzev1%2BNmDMw21hgpTouvvE%2B5b6T9vvXjdDypHaD27bhxN03RdR9%2FI9UmVGMBjEU19uei2mmj5p71XcIy1Qumvowv%2B4HxGUHcuj%2Fq%2Bu4fh5XOt8QX5kbkVCadNDvipzhvtql5Vbk5fvTlP0PAV58%2FIcYuYcDQoTZl0wdMYhv0bLHS17Ex2rwtFEQBkt5VDxaatB0Od6EiP8O15FQn1ecs6j%2B6puV4N%2F34%2BmzhxljO%2BID8yyMT1CL4LqjYu0Tc9dOHEaz86gNKH5%2BV9fUvzOxBBTtCqrR0SvvuwiONEr0SwuoEXMEaKbDtr%2FoKv%2F3ZLnv62maMuent3A2r4fNL8C7ZXTnclLi6IzK5I2izfu0aok2y%2FwQ4xAJckr9pREGB0RDf7zFhHee4gCAiXTSNBxD74Waen4JeKBt4ppUwXldlQSPY4LGo0iUtYSTuV9Wa8bZDfuNfIPHF34KIXgbKx%2F7m5Xg1DAAApHbWK9ayu89xu7eSR2RqKN3Hk1429%2B%2BL42plvXbn3dUj95FQDEXThfsAbcgeEQHBxWWcFAPCk46Xj1y6pXLG7d0l2TXenokuY8gNiyYHzairLhROXpHA36b%2FJgUCI3pFctC2v36ADKEAg8s9geNuot8IJShPyVaqcZswD2jvwulA84qq07VHOeN2R8I2t3KKu9OimjAwgbPAZth%2Bn3mgwIEtSl%2FXw934RQNUfoUAApuamvuo7C4HweBr73VFdsamxnc3rpyp%2FqYI3uAdUDQC4o1CeivU42V4jH%2F08duk0955vEuTN9UYUQADAlr6zaAqeSEGkj2hbbEdFiN2XWvxFVqfZiIiqaiJkFxWDppr4dYrlMg7mMEVPGc0DH%2BpPtjoiZcPmCjDbbqPzvLIQ%2FTYDRMnDoBn5juqUbS1af4RQbkld3cXeuTmAaN8DwhToQpR%2BtORKqnXnospZsHlO4d41XbNOZu0AWiEsUwOXDvVuXTzx0KJSFcMVynQoPny0%2FuAD5ubNl5eNkvwHwsNPmFsgFiBabrLjKQS%2FP3XTobxmh1XMYTQzwRxPSm5qGGFr6%2B299rPdaWZRfmH5yfSedI8wGC22nGmJtnei7etur9Y1hxGMVVQ9%2Fs4IcxmC%2Br%2Bh9%2FfgwrXpG9u5278KfkcEw4sc7lYpQ5eq6winQgo52nF8f3jEr2cBFTPnZ6MlbiuG4bU9UZ4wAgD2hUaN8TbGZDSWTB2XTX4uT1a%2Bhx0PE02L7WQEw%2B9L3rXH2wAMqKgSYVE14JigMi2ZmtqW2ztDIl0y%2FuyLT6H3VBF0Qel4gO2J%2B9%2FTkG2CKFKdEkCRcLnpz0mly0libfLtruicKzK3fQJkNPTmTLeDVCOiHQBwKCTN%2BBZD0GyzEohjA2zvJ8pwrTcEdQGFhKROpY0vB4ylR7swqIZ4zZoKxsx5bZmcsc6ae%2Fry3tsSl3Ea4EwyCSBEUzGscbGd7seQ%2BxNLgoVVkJSyGMzPYmlZIVG%2BxEi3ZSJWBmhwQsUIgiBOxf6pIR5obnxj8zqSwACAYg4DcYQMCi3lBiBqqh5f3uoJ5DkS8zJ%2FaM5cty3EoCqvkgCA%2BbnEqxVpR7oLqgokdAAAkk%2BY5OQvxzdKySUj7EeuzH%2B2MP5DYv7lA4FbjtGP%2BM0xcx4AICMb9AQ3EXZ9PNXkkqIAJyQZJKJU3c12pgtD7k4%2B1Oo52kogkgYAwKOSlMWgaVnyMkKeDk%2FIWEbRiwNvkL%2BWhXAmvoKxhkSZKzZgrCpl06gMAQCKjQSKJqDYnz6XDg4mozO5%2BxrTF%2B%2BKUahMsLGpuYa5FS6cIACGAkUEAJRQwtrpxoW2WKnQeeL4LkwssOk8TnscM%2F6QHWvWF0Lsx79Oh%2FG8wrhpMLXcN9UmIbKIIaD%2BNovBj4ElqX82G7dYslBWUc0EERZlJ6nSmOxm6Na0xmCorMke2uvynNrMU80IjsvkpHfgCZnu4FQrSfVwrMmk6jFDTTI9zgYQIOQbmoL8w7XuFSOaIM%2FPOEBxVEzW2x4%2FGri%2FOpAmPRzhMsl9BQBbfkHx2LL8Mleo9IOfVNzbMNeyvV0P7%2BgO8ypCm%2BBAacJ8Q0836%2F2c89S%2BebgVzyPkiFFBYDMm3ZYKUs6J2QVrd%2FVFvQyQNUzSIIYgAOBJCU%2FKCAB8iYFKSuVKFYQQQU6agzyVL3S0HHbY3Z4kJnlpiIH0KCsRkzQcUawkogFEhlRnRrZSiKgK8XzjRbwwXLLIAVSRwiK4Mls%2BC%2B89GqscYzt2DPV%2BcqxfURQKx1zYz9tQvuA9%2B2B2K507IAUAgHHO6sPhQRfTXSP0vEbQeXS3EkSE69MJC44Dp9Ugji0wIoKKKFBlMFTUyH4eFTWNwfCohGjwVmsBjlvbWptOYelJWWisWhM7NN8or79mkJ4bZtHVpalOTnbTGKuqFkIjUERQhSIjRMEwXjYZ9EcbxhoWkGZGckrNw%2FHony8cdkWZabN%2FwoV5W1SC2ditXPXB%2Fuy3%2FBHp5xoheip%2FxGRuov1gfbDk0fOGPDS9wpLuGeM8BrPUbqsnkBwryBgEwIppioWU3TSkUFTWUAniUUmxkkKBfgQ033AWyDPsCOyd29XeeDItA7OQiPbFg0dthQt1lrJLKnunh%2FpSUxyYoGJxCQAAFAhpVHZSTEsakOgleUYNUA67qb2juLebnObdc1hXfvVb39xRpe2TZw631FnFjlbMtTKTJentGZ3zFDb%2FUqbrNxyKDY%2BYSxZ81nLdps6kMXtm1u7qow6gZllM5OZGtboL0VKACIlESsaTMkQQ2U0pNpLs41FZuyq%2FBqZ3Iyjmzh2f7v5EFIV%2Fg4XO2vec4qu4WKWgzn7p3N8PPmd6IMSXGlFelW0kokGmndW1s4iiTVFsbVEeJ2hZEqwGqbHOc2neJgDQ9bxnlkOeywT%2B1nDV0tJlJ652vxRM%2BL6Ugmg%2F6eIj5PSDw95Y1nTz9XSTi1CPYjk6ITDSVMOzWRShyLIaI9E14fTksjw6IuNhEUDAVRipLg5LyXyZKa9DIpjLusQFPDoUKmE0vjLUO7A7DFyV8eRPFoy%2FC7RvFyM7HEbOZPMMtpYMDYXUvv5Ys%2FzYseSIVnYwqxqyoabF1tG6QSK5t603y2gqzE1kWbu2dI1No94N87JGunVPHrQvrvikJ6jvAicN3WYbotflKvM9ykF%2FkKMt%2F7oMH85%2BtC5eHIoMff%2FaszBV%2BSJCL3K%2BPZY%2BwqUcJGFAUbQhyO1Jyrp0m12HdLGoZiQwVjXVJM7xcIkjUs8Ey3iMHpvTng7sDMlnjbpwndU%2BcPBystoUYjSZaec5Ya7IgDZy4V0uK%2FR4h%2BeayyfayXRVrxEBoqDZErpVJSZEhpKsblcRJgMnlMXbP0RHT2v%2Fsn%2FGtqbAt92pZjJnb2vxG2c%2FsberPILkDKAJwvNs3DPzx5U7dMuqEwKhBwAgAFzJvHa27%2BijR%2B%2FvMpe01x79oEOTFf5vI%2F8Y%2BQhD8xhFoTVNea9dZkWpxmNfgDGH4xIiayObA7PGjt3VKwQrdbME9LqiAzJwWEauHDlpEY6f1B9PtUc4nFljzrpCtN%2FJm26EEMD4xnCq7rGujhiJNarQg4K1XlwxYBqNHkjwiqh10KospXx7qHHc0SHMgWNEbkMSvDGUv7y86MbtT7wz8YEp6Oe%2F1GLJ9LpNTCyZauqPzTfHEaihirjE9tSlJV%2Ffte%2Fpf5xjvcva8ZmQ06vPvbPgb%2Bo61eAnrPoAQ8jbqqOtZRSiQsWIRwi7M6jILvrIMN8rnnCc5Zg%2B7voC7dvY71d1TCAoI4afag0%2BTdREUtSQ0fMOdUtv7fcytlF8vCWeo%2F%2Frxd5tE2wdE3yRAgJjFaqLF7MZRFBbelWN1SwI2l9LvDr%2Bab0USlHWcS6ywqlvxsdfsvXl%2B4a8%2Fee8JTax%2FUQVcaPv4TbzkxsOz%2Fgi%2Bq5UWq7uXTvqMq8%2BcuXu1yTaWe4yF1lIjTIMh9tn5Gz6Yr9NCWI4hYsqEbCRZD8v%2BnR4VPoq1R0YbxTydBABdEs6M9KiEvii%2FdyD3Zs7FM7j8Z7azF%2FV3cXYK5fX71i%2Fgi9zTUC1Dl1dyj%2FD%2FdaxhMoDqdJEt3OooKlGPENCi4KiAOZPEFSMf3PEndcdfHXEZtQj9A820NcP8y3Z88p497Z1k6%2BuS5Sv655xKDIkgXo1nEQldn26Z67vy4vzNiNAe6rqVgQMemk49my9MHu1P4xbXETL61Meunp30ViXOK9bkDUJQXUWIaEZaKJfwFNyIJtR9DjZJ%2BAxiS81oqKKRUWxQF%2BMu99Z8meaps8ACyMcAh0RogLYYunCk2pyqovs4kQriSpQV59WzYRmwPCElI%2FxWggRAbDnKe4sRWuofWnog3c0%2FbXPUnKdL3LLrFEv1u3%2BJnrh1h1zyuiD5xXsv73iA6MWQlQFkkxAztofGnH%2F3rsx4GnWFd%2Fsjt143ujlTbuqzYUOuX3lpMVLD7r8mMFl1owICKZ4FKPO8WIfxDS6g02c6yJCIp5SUAXKdpII8kRUlt00hPCeylkMM3CvxL%2FHgiLLfOzgSE%2FOTlsKD0tkgAcIQFkVEqiqQzEAIAoghgANZleichPGkVqlV0MRWFYqALB%2FBXX94upXPmwRP3hpb4IVnpmonzc4b%2FIK8e3msR%2BhMKLgUcZdKHRmU3CPaF9oD7166dibPtq3sYPZvGxPVNOVoIffnXzXY4etu1Q7AKDDQqAAWFU0xPXFVSeeCEpZDKJAqpvjKk1Q0BAIiagEMVQx4UWiodzzqxp8Tl%2BnjATqnXTjjUUxqk8gYpJiIWUnxZcaIQYggkjZjGoiiIikMVgpl1QCOJajoagGAMBxWFEuDDHWfjX5Yjvd0En7Eo7SnT38psagKMmLCrH6u87ykApEkCtKTVtunjyOiPTzYGdT79cxuluf24U7L3SvXDH%2Bplv3ub5Rvk9MRqxEGgAYRmiSXdOblpwU0CDVwwt5Oo3GMEHFo6KYzQglhnzN9FhpQsevSadTp7Xx9L7Q31UTl6czuqPFQkvNuCyqk2ea0mI2ozhpujWTGa3DkpJsxPGM6suH8gaMqlR%2BSIehKFzVYf%2B4171s1MN93CfP192%2BhitaxZmBzbS8sdO0Ya%2BgAgRRtnSkybW7DisOiTbv2MtDxjEZXf%2FHsa%2Fn63toWpNVFP4LpqLDewkkux%2BDXnd3fwgjKdWAq2ZCzGLwlIzFJdlD0x1sOeCWTogWF00NCxW6vjZj2WkyPadhQVUVEPmwp%2B3AHf4C2W4x7o1J2TTGqnQnJ2YxqgFHeRWPSECDYr4hT%2BWRsJ7M%2BzH%2B21Fner47Xyawi44Mns6EXx%2B7mFdzV3XO%2FiYwKaIvfqBV1ig9AOAQyDvSxyOIWiLtP9%2B3Y0HO5ixDlMC1%2FDwJALhiWvOUrcMEEgcAAAR0mAlXALcb%2B5xZSD%2BnYmlF0%2BNMWwYV1MwoK9OS1gz4oSE5VzVZp%2FbWLrDvVBSp6D9kgc2kGUuZLWfSmKYVB%2BIGxUVRHVxmmJnqE%2FTVCanIYPo2InlpKipZo1HjTNmioci%2FWIgkiGu%2BLZUJDACgYcgWybm1ylGsZK5wfPBw5WsZE5bAvRHJIqu4DhecZMwkhkAEGAo1k1nJ8ko%2FYBhTzD7l99%2FfVQoRAAHotBAjAjiF9I8yjz7eExKKDNrMGFYAABIxSURBVAiETCOnmgjrVwHZRSs2QteQJkLi9ixLrZy7NLtgxqmNPC0LJrPVdParxbJcNmzOS%2Bs%2BXtG1XcrTYbyKsYpYasQiguKkNCM%2B5nB8Ska2zkQIANA8GQAAIXLLxoIu4idNjRSnjqkSpx5HMMWcKpWpi3vc5qEZdYgR2aB1hTKrjFlRLOJWpeszjvkKSf7QrQbvmh78%2Bn3Ll4oLANBuJWA7gLihGFOYdk5jMMlJZ4aZ6S5eYzAAISpA0UNrdvqJoivu%2BN0tJHma8hz4lT1uOEGUVIx6felfNly9bEb2aENIVYwE0CCOIqWKbeSG%2FqEJWZzCkyk0A6A1TwUAvLnLuYb%2FMVahWeXKXZHNK7oerE5QFSL7UIL8HR3XLp6x4JPho648d8HepmSO7t4Ue1tSQyD9nPnwXPexlUbpX8dwDNXentPmkXgAQIeZtKqYzEbX13dnRls1GlPsJJ6UNQZNTbADCNC4RMQlIiLOGDnl11AA%2Ft2e%2BKkTzp464exD29%2FoD4RN7Jua%2BQI2dmzTXm3tOHuBTVjQH8kYoM2mHe%2FU31tXDKnv7V9wJHFTXcqmQHaMIJ4v8Gh2a2%2Bx1y6bjKbvsmwoipGUKxnDoqjJd3tdJqppa3TUc%2BbD7xgM12fK57MkqWU5pH%2BMb7nowOAuC0ECQEvY3hI7RFA8KujrkhBDUUGhEETTYyhErimauWTG1RXlp2nkOQ0L3%2B4%2FeNb4sSd7xps72Ji4PGq61iiu5%2BgxB%2BfFAhI51RiX2zEiVxVEcPWW4gxF%2FGC%2FRYXCJF47j0%2BLvq9bRrbTJQLEz08cKrX9UJiBGKIoqGF1Zo7Czi5hty%2B4vZONBLS1OuI58%2BG3DYbrM%2BUL2AtGJJZ0dr2m5iQA0KdBdncyAkluqAVImkYglqBmT8V9%2BSOWnnPDjEnn%2FhK2KElHjzeMHzns17Lw5vGUO7u%2FNGfg8DvSX8%2Frlpgzb8WJBU7wLs1aEADzXaq8DydLlAc25Tbxht8didxUlzJCTZkqKOdIaY6uDpz%2FQuyyDseo7wqWkJOKkB%2FKk4jVanq95%2FyN9jkAAMRwxc6GTQuxPZNv2sYn43CdHnv%2Be794fHbvrpXmbhotCuC%2BHNgr01iA93DCkryymWd3p7D5ntLLC0uGDgj76c01U3JPWaFTFOVwTS381z5vt9uv2dguSdKAzzg8lWb29QR1RRH1psBMZ5MIERELcE7tx3b1UPHPmM0ruv5Qn9DNyghPJt4uM0zfW%2Fz8odJ7M3e2O0b%2FULNVCH2K%2F3HSKhrVqX0f6kIEOWSaswwuXr%2BDntNfuXsOKj4aR3wK8ry57hLn%2FfH%2BfgKRA1iBTaZ6eCIuXWzRLhpWm9AmWJQ1VvvAhb9ddf4XWsl8C%2FXdT5Zlaxt%2BzER%2Bn19AUfTTqvYndnSX6GWfw9rYF3k7ZON7W2dW%2Bn45or%2Fh69rw0O2NO1GkMsvYU%2B4augtELimOmzdTBUF0dFqkL8jwC7l%2FtlHv7PVsYxyaBvo0GaYbcVB0IbF3lvh5OAOr8UklWGbqyEGJeNxqtSz51H%2FINbMg3TQDbOtjKZGyKu2rD3L9LEkc3Iuujtlsg7nCi1Iwhno209mCJtMwOkTdC%2BxP%2BpwzhhQ0hdFv2mSPcwptzP8lEcFYfPbnPWlBfn56kaKqf99Rd9v6upsnlRj1up%2BwAAAYU%2Bh9%2B3DPY43k0YZWmxzfwVkPJPByGBqc8%2FNOuZ6ItGhl3TZlwhfdtiEGamz20XJkqBs9Dqpo%2FNJM%2BhL%2B7x3Gx9pyrbJ87ahh%2FfGeAEoRQUEo1UZ2fLl0aKbSHMPVjm%2BJObkWZsHgrEQ8brFaf38ISDrbUvq9udaDF9Ff1O%2FrDvoO0H4WT0iKjUoVGPcGDB8pWRZzvPSytApBrI1gxvOjVe%2BUSm9HjL%2Fq8Ky9bFF3X%2BrqGefqdIYT0cqyfPFH1Ueh261EbYC7YkP3JxHTsvH6ieX5P9zz406JYdi784rNGr%2BOdT%2FR7wMAaDh14z75cEvXiYOmWfa2vXK0fC4h8RnCdn9NTis73uYsLytX%2BLsTT6dM83aVrkG8KoHuIuwH%2B5uGiQUIBEKxkWlOVeWSu5vioZTuI%2FHqAf12XSCPoXEFL%2B%2FQduMxSfFQAEE0A043p1EZwpr0qy2eGQfKPhxhsNyXGOZRhg25VYHUvYdGaAiOmXzb869%2F7uvWnzUZ3bPm0DbJCwAIUJ7Fx40dhPtKa2ThpEEn3vOTeKEwy%2F3GRApVJA3%2FfsZmSNP8TcH2%2FtB3PyGEiz6uOgSyVJ0V5IwmIZLEbPdUDXEXjPOnHtnvn70V86CCptgo1UIqZuKNPMcKLAQUjexiAYYCCNYFOo7Hc%2FrogfewGnKkgpjfr48qJKbRKJJRuUEmIV8nuym%2BxKCYCSmLVmPqih2G2fsqVh4cGlTxl1unBYENyR4uZw%2BBCLos4HrlqyM%2FEPHSl0deC36%2FBkOcBACUaeG%2FzR%2BK%2FLT28fOo6dLxg%2B7yJU680oPZZ69u74%2FEAADVze3reA8AwCAFPxx%2BzfRBfZg515%2BS79%2FsHz7p3vkT6RddWfnpNJaWUFamuni6LaMRCJZRrEWEkVD11YlWjvGhm%2F8gLhuS2QMTwUw6zbFsJp2mMqFBiW9fyfpLRwD%2FWoxgrFKWTAIniQoq08YCFDVUxVUDLrsZ0ceIhYYF3ilPPLK7JUau7ZDonLJbHS%2FppRAAAGL4A7VYLJ4AALy3q%2Fa%2BBubEeodRSn56QbbZ%2BJMpAwbs6JEVed67B78Uv98mKS78TMH9H3fct%2FG6ibwgln4a0VB8eckNpJQwqUE%2FMebJhnuFYN%2FVOdrNI1VC2JdIK3uOffnXgybeQck%2BHSJoqKheOTSxdFigLYofOk6IAB9kGaV1tOetzSAA9FPQKyIJigr%2FTs0rzNnR0uy0S4NdUkWxPPqJ3DhBCgV6zUhgSRlPyhqDWRV5vqPs7qse2Vrbt%2BSwUuaJXK5bbsKTBpfhkWMPhMlChya03T7is8Ot1x0C8gkdU5gsrJwILh43QEvJABE0gRMfXz50GBIEACCaenv2K63JnAuyl1%2B24ksExZ6tkIvF%2Fce63JKoufPorfWlj7vuOqss%2BB5X%2FG4NqcS%2F8Yd1H4rOdJlRtZJkD49lZFWHIYL6ZP3iy%2BpXbTM9mMieF3CqKRy3IoCzqYVPJwkEWEShy1OxViw9aP%2F9C%2Bm%2FT%2FXv3N1dophwTU%2FQbSzdksE4FRFUgCJxE83I%2FDOHuCXNNtpAPl38qD%2FmJvWUv4t%2BdsgzXimweo73vb2N1x5CTqQA0dTny7kBKQAni5osJuMXl5ae%2FUmbR9uR4shQyliCHSfxtsmflq6d7cq3jnt%2Bj2YjE%2FXHBjmZRHa2toT%2Fc5k6eXdz%2Fkb%2BH2zvB6JMoFDjy4x4WkZECHHUxEohMxY3lHwDSr5JA5AGI5NbXBMenfaXhEGvtOfL%2BxfbH439SaX0AADwL%2FDDjCaoL9mN14sF%2Bqwa7vJiNFdn%2BLg77xXv9bbuwJWWjdPcu95suWKyZy9QlSZt1Oqmi764rPiT6p5nO83aCT2OCNTuyYrce%2F74AY09KQsAgGyXfcslyvRPpyASuajw%2FRS070pcVIoevebTow%2BePW7FxefdsMZdQqw5r3Dfio7LZhnXY5j2wKRVHUTrMupuJM6OTh%2B4xJHvrbRbGGNlaUV3%2FVN%2F6wAAAJxPZMO2En37IOJQ%2BT6qvwkrGanE1ukK48hVujd71NwmtrAfL1UpPUDA41c9PXrcjHa%2FPxKNaLOIv335jzfwq01F%2FffanxuZ5f%2BkbgqhZBYPXtPSZ%2Fk8fjlFTv3igvz7tnWszXggfmI%2FHbzdGXzh4gmneG%2FmNL2v7f2h81a2WPHqKmnyg77HipDamvTwV5JPXedOPjOz5IWdbW81xNLGwhX58%2BKqnZTiNKn2Jm2HpbO3J87lUMcMh7yw1DhncE6sc1ld10pN0ZxagKEsigBbj4ypXH0wOF0g63AxWzMfIjJPj6TwXoe%2BIcJRfXjBEHsn4%2Fna6SnZdKzj%2Fab01gAiMpY55PvX5K%2Fsi%2BhGFIbq%2Bz12A7ui7rxq6dx7R%2BWO9lmu3RZuQX9SB0U09S5P5C%2BXjkOQU52eT98B3B%2BJzfu0sScTebn0TkHBKVw9Ehv2IvdiRabhlfNL9BRx9WeNE9ybjGpkcna1lJE5yvlcyz1%2FKn%2F4WKSkX8v7KHETKXMj6OZB2t6CzLFZo7wGHZEOHQjRT9M9MafDgSBIJBxG9RjuCRvJoJ5973jqxp6%2BPT2WQXvFKw%2BzJjfZCSXxkYplQcn9dOfj2ybNbYl4gcht7Z%2ByLzIaaJ6PrhyxpimxrAUVGOuJyDFFfKo4s3TO6FN0Lnwnpz9Zex22b64Zdt3K6mf8D13m%2BliLBHsMRTnysUeK77x7073Dsoa%2FMc15w0clMfO5qs5McqEpuXV2LJJmcacTZjq773M%2Byqv0K6knrcaOL%2BWpTx6yuhnolMYNLaugLKzPaiVQJArihMHs7%2BkKpXs7%2BUm9kl1PnVuUbmWpkB1LPjfsOSmegrjBiSU0CLYEz83Smp9qeaovYy3lW2%2Ba4rjyy0CxYdd7JW%2Fd2%2FFKNz74O9gGKfWP8cQVE8ec1kDwK98MAABomvanzVVvNfgnO%2FZ9kFi8vPCaWEvIkW%2F4o%2F%2F5NJo9FWn%2FprZBMbhwk%2BuCkgPDxK80kunnnG1C8cX2T1uM095qufTvI%2B%2Bv6sge6uvv61Yws2lPcPSIvL6mLpPVSbztv%2Fz27NcDMXKQs1NMCi6biKKgX3DrKPmB0Fvv5V3EYTaci2I65s9tf2jXKrRoCCZ78XQ4r6iyF1AvV96TZjGrSSVNzMLjKyGGl2rhlXOyhxcNcAgaUH7tm4QIgpxVmjXSzLzaUGCSm5xCw6yh%2FkQKm5f9VaI7ug1Olh0liJDS4t2NAftObvZxMGWsrzUfHHe5QD03CAXaRHeNwGuyoOptTH%2FKPN5eVd%2FvmZjbaJP80SR966BP1rWd7ctFcnXdQaJcl2wnHPbRnqb1XdMGOTp1Qt8zHY8cjI5oi1hhbwNgI8CcTWfn3Zj32kLPRwpCmRlxR0NhsS2wsXfa79zcmisG53v%2BjUaJX%2BsLP0gynXl4c90qf3istuHy8h2sxpCZMIKABnzy3%2FuWsJgFC7UgqX4gZqDRhdIWk5UgSTiEPhqU3DeUf7arpfi87F0sMEGe3RMZP63oGCKkDwfLFw3f9Y%2FD04aXpAxK4K2mSx6ueG1nZPzB3tKQrrKdL4DpGOBiSLxHs2ZDS47qyM9n9%2F1t7CMrGmZfnL2hL2kuzMr8tfGa9vSoF6YVXzi2HPl1r1H%2BdhYAAADA%2FY2dd33dQyEHxJTwx%2BHLu1J2Fx0PxsgU6tkQm8eQalWgMCOggI0gbBQaHICxANpkRlPZ5oSLjloYlhfQseZDgSgd0jx6NdnIljoMGY02BWVvLT9cz3axwAiEJMLGkXQIWnM0gxMxOqcbN%2FKICcWQ7dJF11pfi8bQq4vX1SQHvdtyxcJBRX%2BcWWE2Gv99c34jCwAAoGnayn31T%2B0P9yvqve7HoyG1PC%2BD4UCHcaJK1oV825MzGpAJEqCRdBjwSSCxQxjxzetmtSWlnowa5NSeaBJjDIoGeY51mg1mCnMxmE%2BPpYM9d37VrFJ6QJug3q5afRBBAQCL9c9ONW8VFTwWR%2B5n1xCQu93y3PL%2BJRfm0I%2BdW1RykszY%2FywL34ksy6sONC07EmnLcDPITweZm216DkdVCnBZOXhnm8qhpnuSq77rX7vMEll51Y%2FpzI729rz8fARB2v3%2BgsLCH64HgkHfh2GV%2FPlLE0u9j2fxNQBqXkvmjqZXE4hvYZZ8z4SsyvxfuwqeTP7T7zURBHHl5MELJ8Fva1vfqL3ttY5odqJ1gXMNCpQNxyacZ9tMkyhMfj9LS8w%2FUef2eGqqq10uVzL5k1Os2%2BUySy2xX7Cwqnvubfb6TyKL5C7n70fmXDfa53We%2Fs2wXyNn5qtVCIJMGVoyZSiIJ5KfH8tZ3Tpmb19SoKweMtis%2FZgLLTH9pBO3u6urrLwMQRCcwEPBoMvt%2FmG0Mgu276efa0M0VVKz2nVvLJtumjioEEVP09T77%2BH%2FH%2FrWZzKV2t7Qu7Wb29knNEl6hTZ5xdChq4qy3T9uYOl0uu54rdvjCfT3j58w8cQI7%2BWtR%2B%2BpoyFGGLjQeAd6bjYzM984vDQfRf9HPkL4P8XCiRKORJv7I4NyPRbzzxPhfn8bAMCgN%2FzgCD9Ic0c3K0qDCnJ%2BZX3pP5H%2FBgv%2F%2B%2BX%2Ff%2FcVAAD%2BD33CphLk977FAAAAAElFTkSuQmCC%22+%2F%3E%3C%2Fp%3E%0D%0A%0D%0A%3Cp+class%3D%22Cabe%E7alho%22%3EMINIST%26Eacute%3BRIO+DA+AGRICULTURA+E+PECU%26Aacute%3BRIA%3C%2Fp%3E%0D%0A%0D%0A%3Cp+class%3D%22Cabe%E7alho%22%3EDIVIS%26Atilde%3BO+DE+AN%26Aacute%3BLISE+DE+PARCERIAS+INSTITUCIONAIS%3C%2Fp%3E%0D%0A'),
        'txaEditor_6191'=> urldecode('%3Cp+align%3D%22center%22%3E%26nbsp%3B%3C%2Fp%3E%0D%0A%0D%0A%3Cp+align%3D%22center%22%3E%3Cstrong%3ETERMO+ADITIVO%3C%2Fstrong%3E%3C%2Fp%3E%0D%0A%0D%0A%3Cp+align%3D%22center%22%3E%26nbsp%3B%3C%2Fp%3E%0D%0A%0D%0A%3Cp%3Eqew%3C%2Fp%3E%0D%0A%0D%0A%3Ctable+border%3D%221%22+cellpadding%3D%220%22+cellspacing%3D%220%22+style%3D%22height%3A100%25%3Bwidth%3A100%25%3B%22+width%3D%22688%22%3E%0D%0A%09%3Ctbody%3E%0D%0A%09%09%3Ctr%3E%0D%0A%09%09%09%3Ctd+style%3D%22width%3A688px%3B%22%3E%0D%0A%09%09%09%3Cp%3E%3Cstrong%3EDOCUMENTO+DE+REFER%26Ecirc%3BNCIA%3A+Auto+de+Infra%26ccedil%3B%26atilde%3Bo+n.%26ordm%3B+xxxxx%3C%2Fstrong%3E%3C%2Fp%3E%0D%0A%0D%0A%09%09%09%3Cp%3E%3Cstrong%3EProcesso%3A+xxxxx%3C%2Fstrong%3E%3C%2Fp%3E%0D%0A%09%09%09%3C%2Ftd%3E%0D%0A%09%09%3C%2Ftr%3E%0D%0A%09%3C%2Ftbody%3E%0D%0A%3C%2Ftable%3E%0D%0A%0D%0A%3Cdiv+style%3D%22clear%3Aboth%3B%22%3E%26nbsp%3B%3C%2Fdiv%3E%0D%0A%0D%0A%3Cp%3E%26nbsp%3B%3C%2Fp%3E%0D%0A%0D%0A%3Cp%3E%3Cstrong%3EIDENTIFICA%26Ccedil%3B%26Atilde%3BO+DO+INFRATOR%3C%2Fstrong%3E%3C%2Fp%3E%0D%0A%0D%0A%3Ctable+border%3D%221%22+cellpadding%3D%220%22+cellspacing%3D%220%22+style%3D%22height%3A100%25%3Bwidth%3A100%25%3B%22+width%3D%22696%22%3E%0D%0A%09%3Ctbody%3E%0D%0A%09%09%3Ctr%3E%0D%0A%09%09%09%3Ctd+colspan%3D%222%22+style%3D%22width%3A492px%3Bheight%3A17px%3B%22%3E%0D%0A%09%09%09%3Cp%3ENome+empresarial%3A+%3Cstrong%3Exxxxx%3C%2Fstrong%3E%3C%2Fp%3E%0D%0A%09%09%09%3C%2Ftd%3E%0D%0A%09%09%09%3Ctd+style%3D%22width%3A204px%3Bheight%3A17px%3B%22%3E%0D%0A%09%09%09%3Cp%3ERegistro+n%26ordm%3B%3A+%3Cstrong%3Exxxxx%3C%2Fstrong%3E%3C%2Fp%3E%0D%0A%09%09%09%3C%2Ftd%3E%0D%0A%09%09%3C%2Ftr%3E%0D%0A%09%09%3Ctr%3E%0D%0A%09%09%09%3Ctd+colspan%3D%222%22+style%3D%22width%3A492px%3Bheight%3A18px%3B%22%3E%0D%0A%09%09%09%3Cp%3EEndere%26ccedil%3Bo+completo%3A+%3Cstrong%3Exxxxx%3C%2Fstrong%3E%3C%2Fp%3E%0D%0A%09%09%09%3C%2Ftd%3E%0D%0A%09%09%09%3Ctd+style%3D%22width%3A204px%3Bheight%3A18px%3B%22%3E%0D%0A%09%09%09%3Cp%3EMunic%26iacute%3Bpio%2FUF%3A+%3Cstrong%3Exxxxx%3C%2Fstrong%3E%3C%2Fp%3E%0D%0A%09%09%09%3C%2Ftd%3E%0D%0A%09%09%3C%2Ftr%3E%0D%0A%09%09%3Ctr%3E%0D%0A%09%09%09%3Ctd+style%3D%22width%3A240px%3Bheight%3A19px%3B%22%3E%0D%0A%09%09%09%3Cp%3ECNPJ%2FCPF%3A+%3Cstrong%3Exxxxx%3C%2Fstrong%3E%3C%2Fp%3E%0D%0A%09%09%09%3C%2Ftd%3E%0D%0A%09%09%09%3Ctd+style%3D%22width%3A252px%3Bheight%3A19px%3B%22%3E%0D%0A%09%09%09%3Cp%3ECEP%3A+%3Cstrong%3Exxxxx%3C%2Fstrong%3E%3C%2Fp%3E%0D%0A%09%09%09%3C%2Ftd%3E%0D%0A%09%09%09%3Ctd+style%3D%22width%3A204px%3Bheight%3A19px%3B%22%3E%0D%0A%09%09%09%3Cp%3ETelefone%3A+%3Cstrong%3Exxxxx%3C%2Fstrong%3E%3C%2Fp%3E%0D%0A%09%09%09%3C%2Ftd%3E%0D%0A%09%09%3C%2Ftr%3E%0D%0A%09%3C%2Ftbody%3E%0D%0A%3C%2Ftable%3E%0D%0A%0D%0A%3Cp%3E%26nbsp%3B%3C%2Fp%3E%0D%0A%0D%0A%3Cp%3E%3Cstrong%3EDESCRI%26Ccedil%3B%26Atilde%3BO+SUM%26Aacute%3BRIA%3C%2Fstrong%3E%3C%2Fp%3E%0D%0A%0D%0A%3Ctable+border%3D%221%22+cellpadding%3D%220%22+cellspacing%3D%220%22+style%3D%22height%3A100%25%3Bwidth%3A100%25%3B%22+width%3D%22696%22%3E%0D%0A%09%3Ctbody%3E%0D%0A%09%09%3Ctr%3E%0D%0A%09%09%09%3Ctd+style%3D%22width%3A696px%3Bheight%3A17px%3B%22%3E%0D%0A%09%09%09%3Cp%3EReferente+ao+texto+do+auto+de+infra%26ccedil%3B%26atilde%3Bo+n.%26ordm%3B+%3Cstrong%3Exxxxx%3C%2Fstrong%3E%2C+onde+se+l%26ecirc%3B%3A%3C%2Fp%3E%0D%0A%0D%0A%09%09%09%3Cp%3E%3Cstrong%3E%26ldquo%3B%3C%2Fstrong%3E%3Cstrong%3Exxxxx%3C%2Fstrong%3E%3Cstrong%3E%26rdquo%3B%3C%2Fstrong%3E%3C%2Fp%3E%0D%0A%0D%0A%09%09%09%3Cp%3E%26nbsp%3B%3C%2Fp%3E%0D%0A%0D%0A%09%09%09%3Cp%3ELeia-se%3A%3C%2Fp%3E%0D%0A%0D%0A%09%09%09%3Cp%3E%3Cstrong%3E%26ldquo%3B%3C%2Fstrong%3E%3Cstrong%3Eyyyyy%3C%2Fstrong%3E%3Cstrong%3E%26rdquo%3B%3C%2Fstrong%3E%3C%2Fp%3E%0D%0A%0D%0A%09%09%09%3Cp%3E%26nbsp%3B%3C%2Fp%3E%0D%0A%0D%0A%09%09%09%3Cp%3E%3Cbr+%2F%3E%0D%0A%09%09%09Obs%3A+O+restante+do+texto+permanece+conforme+o+do+Auto+de+Infra%26ccedil%3B%26atilde%3Bo+n%26deg%3B+%3Cstrong%3Exxxxx%3C%2Fstrong%3E.%3C%2Fp%3E%0D%0A%0D%0A%09%09%09%3Cp%3E%26nbsp%3B%3C%2Fp%3E%0D%0A%09%09%09%3C%2Ftd%3E%0D%0A%09%09%3C%2Ftr%3E%0D%0A%09%3C%2Ftbody%3E%0D%0A%3C%2Ftable%3E%0D%0A%0D%0A%3Cp%3E%26nbsp%3B%3C%2Fp%3E%0D%0A%0D%0A%3Cp%3E%3Cstrong%3EPRAZO+DE+DEFESA+%3Csup%3E%281%29%3C%2Fsup%3E%3C%2Fstrong%3E%3C%2Fp%3E%0D%0A%0D%0A%3Ctable+border%3D%221%22+cellpadding%3D%220%22+cellspacing%3D%220%22+style%3D%22height%3A100%25%3Bwidth%3A100%25%3B%22+width%3D%22696%22%3E%0D%0A%09%3Ctbody%3E%0D%0A%09%09%3Ctr%3E%0D%0A%09%09%09%3Ctd+style%3D%22width%3A696px%3Bheight%3A17px%3B%22%3E%0D%0A%09%09%09%3Cp%3EEsclarecemos+que+Vossa+Senhoria+tem+o+prazo+m%26aacute%3Bximo+de+%3Cstrong%3E10+%28dez%29+dias%3C%2Fstrong%3E%3Cstrong%3E%2C+%3C%2Fstrong%3Epara+encaminhar+a+defesa+por+escrito+ao+SIF+%3Cstrong%3Exxxxx%3C%2Fstrong%3E.%3C%2Fp%3E%0D%0A%0D%0A%09%09%09%3Cp%3E%26nbsp%3B%26nbsp%3B%26nbsp%3B%26nbsp%3B%26nbsp%3B%26nbsp%3B%26nbsp%3B%26nbsp%3B%26nbsp%3B%26nbsp%3B%26nbsp%3B%26nbsp%3B%26nbsp%3B%26nbsp%3B%26nbsp%3B+Ap%26oacute%3Bs+o+encaminhamento+da+defesa+ao+SIF+local%2C+ou+vencido+o+prazo+para+tal%2C+os+autos+ser%26atilde%3Bo+julgados+pela+Autoridade+Federal+competente+da+SFA%2FRS+e+sua+empresa+receber%26aacute%3B+pelo+SIF+local+a+respectiva+Notifica%26ccedil%3B%26atilde%3Bo%2C+informando-lhe+das+decis%26otilde%3Bes+tomadas.%3C%2Fp%3E%0D%0A%09%09%09%3C%2Ftd%3E%0D%0A%09%09%3C%2Ftr%3E%0D%0A%09%3C%2Ftbody%3E%0D%0A%3C%2Ftable%3E%0D%0A%0D%0A%3Cp%3E%26nbsp%3BO+autuado+recebeu+uma+via+deste+documento+em+____%2F____%2F____.%3C%2Fp%3E%0D%0A%0D%0A%3Cp%3E%26nbsp%3B%3C%2Fp%3E%0D%0A%0D%0A%3Cp+align%3D%22center%22%3ELocal+e+data%3A%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3BPorto+Alegre%2C+____+de+___________________+de+________%3C%2Fp%3E%0D%0A%0D%0A%3Cp+align%3D%22center%22%3E%26nbsp%3B%3C%2Fp%3E%0D%0A%0D%0A%3Cp+align%3D%22center%22%3E%26nbsp%3B%3C%2Fp%3E%0D%0A%0D%0A%3Cp+class%3D%22Texto_Justificado%22%3E__________________________________________________%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B_________________________________________________%3C%2Fp%3E%0D%0A%0D%0A%3Cp+class%3D%22Texto_Justificado%22%3E%3Cstrong%3ERESPONS%26Aacute%3BVEL+OU+REPRESENTANTES+DO+ESTABELECIMENTO%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+AUDITOR%28A%29+FISCAL+FEDERAL+AGROPECU%26Aacute%3BRIO%28A%29%3Cbr+%2F%3E%0D%0A%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%5BIDENTIFICA%26Ccedil%3B%26Atilde%3BO%5D%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%5BIDENTIFICA%26Ccedil%3B%26Atilde%3BO%5D%3Cbr+%2F%3E%0D%0A%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3BRG%2FCPF%3A%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+RG%2FCPF%3A+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B+%26nbsp%3B%3C%2Fstrong%3E%3C%2Fp%3E%0D%0A'),
        'txaEditor_6193' => urldecode('%3Cp%3E%26nbsp%3B%3C%2Fp%3E%0D%0A%0D%0A%3Ctable+border%3D%221%22+cellpadding%3D%221%22+cellspacing%3D%221%22+style%3D%22height%3A100%25%3Bmargin-left%3Aauto%3Bmargin-right%3Aauto%3Bwidth%3A100%25%3B%22%3E%0D%0A%09%3Ctbody%3E%0D%0A%09%09%3Ctr%3E%0D%0A%09%09%09%3Ctd%3ERefer%26ecirc%3Bncia%3A+Processo+n%26ordm%3B+21000.032483%2F2024-67%3C%2Ftd%3E%0D%0A%09%09%09%3Ctd%3ESEI%3A+n%26ordm%3B+35746223%3C%2Ftd%3E%0D%0A%09%09%3C%2Ftr%3E%0D%0A%09%3C%2Ftbody%3E%0D%0A%3C%2Ftable%3E%0D%0A%0D%0A%3Cp%3E%26nbsp%3B%3C%2Fp%3E%0D%0') .  html_entities($documento->texto),
    
        'hdnVersao' => 1,
        'hdnIgnorarNovaVersao' => 'N',
        'hdnSiglaUnidade' => 'DAPI-SDI',
        'hdnInfraPrefixoCookie' => 'MAPA_SEI_pedro.noronha'
    ];

    /* change the whole array encoding to utf8 HTML enttities  */





    $formPost = $this->simplebrowser->post($editor_salvar, $formPostData);



    

    echo $formPost['resultado'];
  

    die();












        /*

        $documento = $this->simplebrowser->get('https://sei.agro.gov.br/sei/'. $documento_url);

        echo $documento;*/

       
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
