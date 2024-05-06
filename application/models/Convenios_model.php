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

    function countAllConvenios() {
        $this->db->select('count(*) as total');
        $this->db->from('convenios');
        $query = $this->db->get();
        return $query->row()->total;
    }

    function countConveniosWithWhere($where) {
        $this->db->select('count(*) as total');
        $this->db->from('convenios');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->row()->total;
    }

    function getConveniosPagination($perPage, $offset, $nameOrNumberSearch, $searchStatus, $searchEstado, $searchDate) {
        $this->db->select('NR_CONVENIO, NR_PROPOSTA, ANO_PROP, MUNIC_PROPONENTE, UF_PROPONENTE, OBJETO_PROPOSTA, DIA_INIC_VIGENCIA_PROPOSTA, DIA_FIM_VIGENCIA_PROPOSTA, SIT_CONVENIO');
        $this->db->from('convenios');
        if($nameOrNumberSearch != '') {
            $this->db->group_start();
            $this->db->like('NR_CONVENIO', $nameOrNumberSearch);
            $this->db->or_like('OBJETO_PROPOSTA', $nameOrNumberSearch);
            $this->db->group_end();
        }
        if($searchStatus != '') {
            $this->db->where('SIT_CONVENIO', $searchStatus);
        }
        if($searchEstado != '') {
            $this->db->where('UF_PROPONENTE', $searchEstado);
        }
        if($searchDate != '') {
            $this->db->group_start();
            $this->db->where('DIA_INIC_VIGENCIA_PROPOSTA', $searchDate);
            $this->db->or_where('DIA_FIM_VIGENCIA_PROPOSTA', $searchDate);
            $this->db->group_end();
        }
        $this->db->order_by('DIA_INIC_VIGENCIA_PROPOSTA', 'desc');
        $this->db->limit($perPage, $offset);
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

    /*function countConvenios()
    {
        $this->db->select('count(*) as total');
        $this->db->from('convenios');
        $query = $this->db->get();
        return $query->row()->total;
    }*/

    function countConvenios($nameOrNumberSearch, $searchStatus, $searchEstado, $searchDate) {
        $this->db->select('count(*) as total');
        $this->db->from('convenios');
        if($nameOrNumberSearch != '') {
            $this->db->like('NR_CONVENIO', $nameOrNumberSearch);
            $this->db->or_like('OBJETO_PROPOSTA', $nameOrNumberSearch);
        }
        if($searchStatus != '') {
            $this->db->where('SIT_CONVENIO', $searchStatus);
        }
        if($searchEstado != '') {
            $this->db->where('UF_PROPONENTE', $searchEstado);
        }
        if($searchDate != '') {
            $this->db->where('DIA_INIC_VIGENCIA_PROPOSTA =', $searchDate);
            $this->db->or_like('DIA_FIM_VIGENCIA_PROPOSTA', $searchDate);
        }
        $query = $this->db->get();
        return $query->row()->total;
    }


}
