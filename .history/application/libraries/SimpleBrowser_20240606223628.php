<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SimpleBrowser {
    private $cookieFile;
    private $userAgent;
    private $headers;
    private $responseHeaders;

    public function __construct($cookieFile = 'cookies.txt') {
        $this->cookieFile = $cookieFile;
        $this->userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36';
        $this->headers = [];
        $this->responseHeaders = [];
    }

    private function initCurl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, [$this, 'handleResponseHeaders']);
        return $ch;
    }

    private function handleResponseHeaders($curl, $header) {
        $this->responseHeaders[] = $header;
        return strlen($header);
    }

    public function setHeaders($headers) {
        $this->headers = $headers;
    }

    public function get($url) {
        $ch = $this->initCurl($url);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public function post($url, $data,$disableRedirect = false) {
        $ch = $this->initCurl($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        if($disableRedirect) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        }
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); 
        $output = curl_exec($ch);
        //$curl_info = curl_getinfo($ch);
        //var_dump($curl_info);
        curl_close($ch);        
        return $output;
    }

    public function submitForm($url, $formFields, $method = 'POST') {
        if (strtoupper($method) == 'POST') {
            return $this->post($url, $formFields);
        } else {
            $query = http_build_query($formFields);
            return $this->get($url . '?' . $query);
        }
    }

    public function setCookieFile($cookieFile) {
        $this->cookieFile = $cookieFile;
    }

    public function setUserAgent($userAgent) {
        $this->userAgent = $userAgent;
    }

    public function setHeader($header) {
        $this->headers[] = $header;
    }

    public function clearCookies() {
        if (file_exists($this->cookieFile)) {
            unlink($this->cookieFile);
        }
    }

    public function getResponseHeaders() {
        return $this->responseHeaders;
    }

    public function getTextInputsAsJson($html) {
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $inputs = $dom->getElementsByTagName('input');
        $textInputs = [];

        foreach ($inputs as $input) {
            if ($input->getAttribute('type') === 'text') {
                $name = $input->getAttribute('name');
                $value = $input->getAttribute('value');
                $textInputs[$name] = $value;
            }
        }

        return json_encode($textInputs);
    }
}
