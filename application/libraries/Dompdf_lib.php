<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class Dompdf_lib {
    public function __construct() {
        $options = new Options();
        $options->set('isRemoteEnabled', TRUE);
        //$options->set('isHtml5ParserEnabled', TRUE);
        $this->dompdf = new Dompdf($options);
    }

    public function load_html($html) {
        $this->dompdf->loadHtml($html);
    }

    public function render() {
        $this->dompdf->render();
    }

    public function stream($filename) {
        $this->dompdf->stream($filename);
    }

    public function output() {
        return $this->dompdf->output();
    }
}