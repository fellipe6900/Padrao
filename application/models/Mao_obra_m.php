<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mao_obra_m extends CI_Model {

    var $id;
    var $nome;
    var $descricao;
    var $valor;
    // Ajax 
    var $table = 'mao_obra';
    var $column_order = array('id','nome', 'descricao', 'valor');
    var $column_search = array('nome', 'descricao');
    var $order = array('id'=>'asc');
    
    private function get_datatables_query() {
        $this->db->select('id,nome,descricao,CONCAT("R$ ", format(valor,2,"pt_BR")) as valor');
        $this->db->from($this->table);
        $i = 0;

        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
                }
                $i++;
            }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    
    public function get_datatables() {
        $this->get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    
    public function count_filtered() {
        $this->get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    public function count_all() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_by_id($id){
        $this->db->where('id', $id);
        $this->db->limit(1);
        $result = $this->db->get('mao_obra');
        if($result->num_rows() > 0){
            return $this->changeToObject($result->result_array());
        }
        return null;
    }

    public function inserir($dados) {
        if (empty($dados['id'])) {
            if ($this->db->insert('mao_obra', $dados)) {
                return $this->db->insert_id();
            }
        }
        return false;
    }

    public function editar($dados) {
        if (!empty($dados['id'])) {
            $this->db->where('id', $dados['id']);
            if ($this->db->update('mao_obra', $dados)) {
                return true;
            }
        }
        return false;
    }

    public function deletar($id) {
        if (!empty($id)) {
            $this->db->where('id', $id);
            if ($this->db->delete('mao_obra')) {
                return true;
            }
        }
        return false;
    }

    private function changeToObject($result_db) {
        foreach ($result_db as $key => $value) {
            $object = new Mao_obra_m();
            $object->id = $value['id'];
            $object->nome = $value['nome'];
            $object->descricao = $value['descricao'];
            $object->valor = $value['valor'];
        }
        return $object;
    }

    public function get_pesonalizado($colunas){
        $this->db->select($colunas);
        $this->db->order_by("nome", "asc");
        return $this->db->get("mao_obra")->result_array();
    }

}