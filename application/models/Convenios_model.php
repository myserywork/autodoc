<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Convenios_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getConvenio($id)
    {
        $this->db->select('*');
        $this->db->from('convenios');
        $this->db->where('NR_CONVENIO', $id);
        $query = $this->db->get();
        return $query->row();
    }

    function getConvenios()
    {
        $this->db->select('NR_CONVENIO, NR_PROPOSTA, ANO_PROP');
        $this->db->from('convenios');
        $query = $this->db->get();
        return $query->result();
    }

    function getTop10Convenios()
    {
        $this->db->select('NR_CONVENIO,MUNIC_PROPONENTE,UF_PROPONENTE,OBJETO_PROPOSTA,DIA_INIC_VIGENCIA_PROPOSTA,DIA_FIM_VIGENCIA_PROPOSTA,SIT_CONVENIO');
        $this->db->from('convenios');
        $this->db->where('SIT_CONVENIO IS NOT NULL');
        $this->db->order_by('DIA_INIC_VIGENCIA_PROPOSTA', 'desc');
        $this->db->limit(100);
        $query = $this->db->get();
        return $query->result();
    }
}
