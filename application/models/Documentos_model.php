<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documentos_model extends CI_Model
{
    function __construct() {
        parent::__construct();
    }

    function countDocumentosPorConvenioENome($id_convenio, $nome) {
        $this->db->where('id_convenio', $id_convenio);
        $this->db->where('nome', $nome);
        return $this->db->count_all_results('documentos');
    }

    function getDocumento($id)
    {
        $this->db->select('*');
        $this->db->from('documentos');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    function get($table, $fields, $where = '', $perpage = 0, $start = 0, $orderby = 'id', $order = 'desc', $one = false, $array = 'array') {
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->order_by($orderby, $order);
        $this->db->limit($perpage, $start);
        if ($where) {
            $this->db->where($where);
        }

        $query = $this->db->get();

        $result = !$one ? $query->result() : $query->row();
        return $result;
    }

    function add($table, $data) {
        $this->db->insert($table, $data);
        if ($this->db->affected_rows() == '1') {
            return TRUE;
        }

        return FALSE;
    }

    /* Para uso exclusivo de adição de programação */

    function add_prog($table, $data) {
        $this->db->insert($table, $data);
        
        if ($this->db->affected_rows() == '1') {
            return $this->db->insert_id();
        }
        return 0;
    }

    function edit($table, $data, $fieldID, $ID) {
        $this->db->where($fieldID, $ID);
        $this->db->update($table, $data);

        if ($this->db->affected_rows() >= 0) {
            return TRUE;
        }

        return FALSE;
    }

    function delete($table, $fieldID, $ID) {
        $this->db->where($fieldID, $ID);
        $this->db->delete($table);
        if ($this->db->affected_rows() == '1') {
            return TRUE;
        }

        return FALSE;
    }

    function count($table) {
        return $this->db->count_all($table);
    }

    public function result_array() {
        $result = $this->response->result_array();

        return $result;
    }

    function escape($value) {
        $return = '';
        for ($i = 0; $i < strlen($value); ++$i) {
            $char = $value[$i];
            $ord = ord($char);
            if ($char !== "'" && $char !== "\"" && $char !== '\\' && $ord >= 32 && $ord <= 126)
                $return .= $char;
            else
                $return .= '\\x' . dechex($ord);
        }
        return $return;
    }

    function count_where_2($table, $where) {
        $this->db->where($where);
        $this->db->from($table);
        return $this->db->count_all_results();
    }

    function edit_where($table, $data, $where) {
        $this->db->where($where);
        $this->db->update($table, $data);
        if ($this->db->affected_rows() >= 0) {
            return TRUE;
        }
        return FALSE;
    }

    function getTop100Documentos()
    {
        $this->db->select('*');
        $this->db->from('documentos');
        $this->db->limit(100);
        $query = $this->db->get();
        return $query->result();
    }

}