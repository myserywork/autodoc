<?php

function getToken($html) {

    $dom = new DOMDocument();

    @$dom->loadHTML($html);

    $inputs = $dom->getElementsByTagName('input');

    foreach ($inputs as $input) {
        if ($input->getAttribute('type') === 'hidden') {
            return $input->getAttribute('value');
        }
    }

    return null;
}