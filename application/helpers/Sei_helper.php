<?php


if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('getTextInputsAsJson')) {
    function getTextInputsAsJson($html) {
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $inputs = $dom->getElementsByTagName('input');
        $textInputs = [];

        foreach ($inputs as $input) {
            if ($input->getAttribute('type') === 'text') {
                $textInputs[] = [
                    'id' => $input->getAttribute('id'),
                    'name' => $input->getAttribute('name'),
                    'value' => $input->getAttribute('value')
                ];
            }
        }

        return json_encode($textInputs);
    }
}

if (!function_exists('getToken')) {
    function getToken($html) {
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $inputs = $dom->getElementsByTagName('input');

        foreach ($inputs as $input) {
            if ($input->getAttribute('type') === 'hidden') {
                return [
                    'id' => $input->getAttribute('id'),
                    'name' => $input->getAttribute('name'),
                    'value' => $input->getAttribute('value')
                ];
            }
        }

        return null;
    }
}


function getOptionsAsJson($html) {
    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    // Seleciona todos os links com a classe "ancoraOpcao"
    $links = $xpath->query('//a[@class="ancoraOpcao"]');
    $options = [];

    foreach ($links as $link) {
        $options[] = [
            'href' => $link->getAttribute('href'),
            'text' => trim($link->nodeValue)
        ];
    }

    return json_encode($options, JSON_UNESCAPED_UNICODE);
}