<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Fita_espessura_m extends CI_Model {

    var $id;
    var $esp_03mm;
    var $esp_07mm;
    var $esp_10mm;
    var $esp_15mm;
    var $esp_22mm;
    var $esp_38mm;
    var $esp_50mm;
    var $esp_70mm;
    // Ajax 
    var $table = 'fita_espessura';
    var $column_order = array('id', 'esp_03mm', 'esp_07mm', 'esp_10mm', 'esp_15mm', 'esp_22mm', 'esp_38mm', 'esp_50mm', 'esp_70mm'); //set column field database for datatable orderable
    var $column_search = array('nome'); //set column field database for datatable searchable just nome , descricao are searchable
    var $order = array('id'=>'asc'); // default order 

    // Ajax Nao alterar
    private function _get_datatables_query() {
        $this->db->from($this->table);
        $i = 0;

        foreach ($this->column_search as $item) { // loop column 
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
                }
                $i++;
            }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    // Ajax Nao alterar
    public function get_datatables() {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    // Ajax Nao alterar
    public function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
    // Ajax Nao alterar
    public function count_all() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_by_id($id){
        $this->db->where('id', $id);
        $this->db->limit(1);
        $result = $this->db->get('fita_espessura');
        $result =  $this->Fita_espessura_m->_changeToObject($result->result_array());
        return $result[0];
    }

    public function get_list($id = '') {
        if (!empty($id)) {
            $this->db->where('id', $id);
            $this->db->limit(1);
            $result = $this->db->get('fita_espessura');
        } else {
            $result = $this->db->get('fita_espessura');
        }
        return $this->Fita_espessura_m->_changeToObject($result->result_array());
    }

    public function inserir(Fita_espessura_m $objeto) {
        if (!empty($objeto)) {
            $dados = array(
                'id' => $objeto->id,
                'esp_03mm' => $objeto->esp_03mm,
                'esp_07mm' => $objeto->esp_07mm,
                'esp_10mm' => $objeto->esp_10mm,
                'esp_15mm' => $objeto->esp_15mm,
                'esp_22mm' => $objeto->esp_22mm,
                'esp_38mm' => $objeto->esp_38mm,
                'esp_50mm' => $objeto->esp_50mm,
                'esp_70mm' => $objeto->esp_70mm,
                );
            if ($this->db->insert('fita_espessura', $dados)) {
                $this->session->set_flashdata('sucesso', 'Registro inserido com sucesso');
                return $this->db->insert_id();
            } else {
                $this->session->set_flashdata('erro', 'Não foi possível inserir este registro');
                return false;
            }
        } else {
            return false;
        }
    }

    public function editar(Fita_espessura_m $objeto) {
        if (!empty($objeto->id)) {
            $dados = array(
                'id' => $objeto->id,
                'esp_03mm' => $objeto->esp_03mm,
                'esp_07mm' => $objeto->esp_07mm,
                'esp_10mm' => $objeto->esp_10mm,
                'esp_15mm' => $objeto->esp_15mm,
                'esp_22mm' => $objeto->esp_22mm,
                'esp_38mm' => $objeto->esp_38mm,
                'esp_50mm' => $objeto->esp_50mm,
                'esp_70mm' => $objeto->esp_70mm,
                );
            $this->db->where('id', $objeto->id);
            if ($this->db->update('fita_espessura', $dados)) {
                $this->session->set_flashdata('sucesso', 'Registro editado com sucesso');
                return true;
            }
        } else {
            $this->session->set_flashdata('erro', 'Não foi possível editar este registro');
            return false;
        }
    }

    public function deletar($id = '') {
        if (!empty($id)) {
            $this->db->where('id', $id);
            if ($this->db->delete('fita_espessura')) {
                $this->session->set_flashdata('sucesso', 'Registro excluido com sucesso');
                return true;
            } else {
                $this->session->set_flashdata('erro', 'Não foi possível excluir este registro');
                return false;
            }
        } else {
            return false;
        }
    }

    function _changeToObject($result_db = '') {
        $object_lista = array();
        foreach ($result_db as $key => $value) {
            $object = new Fita_espessura_m();
            $object->id = $value['id'];
            $object->esp_03mm = $value['esp_03mm'];
            $object->esp_07mm = $value['esp_07mm'];
            $object->esp_10mm = $value['esp_10mm'];
            $object->esp_15mm = $value['esp_15mm'];
            $object->esp_22mm = $value['esp_22mm'];
            $object->esp_38mm = $value['esp_38mm'];
            $object->esp_50mm = $value['esp_50mm'];
            $object->esp_70mm = $value['esp_70mm'];
            $object_lista[] = $object;
        }
        return $object_lista;
    }

}