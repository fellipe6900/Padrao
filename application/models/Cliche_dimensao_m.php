<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cliche_dimensao_m extends CI_Model {

    var $id;
    var $nome;
    var $cliche;
    var $valor_servico;
    var $valor_cliche;
    var $table = 'cliche_dimensao';
    var $selected = false; //boolean : setado dentro do objeto Cliche_m

    public function get_by_id($id){
        $this->db->where('id', $id);
        $this->db->limit(1);
        $result = $this->db->get($this->table);
        if($result->num_rows() > 0){
            $result =  $this->changeToObject($result->result_array());
            return $result[0];
        }
        return false;
    }

    public function get_by_modelo_id($id){
        $this->db->where('cliche', $id);
        $this->db->order_by("cliche", "asc");
        $result = $this->db->get($this->table);
        if($result->num_rows() > 0){
            return $this->changeToObject($result->result_array());
        }
        return false;
    }

    public function inserir($dados) {
        if (empty($dados['id'])) {
            if ($this->db->insert($this->table, $dados)) {
                return $this->db->insert_id();
            }
        }
        return false;
    }

    public function editar($dados) {
        if (!empty($dados['id'])) {
            $this->db->where('id', $dados['id']);
            if ($this->db->update($this->table, $dados)) {
                return true;
            }
        }
        return false;
    }

    public function deletar($id) {
        if (!empty($id)) {
            $this->db->where('id', $id);
            if ($this->db->delete($this->table)) {
                return true;
            }
        }
        return false;
    }

    public function delete_by_cliche_id($id) {
        if (!empty($id)) {
            $this->db->where('cliche', $id);
            if ($this->db->delete($this->table)) {
                return true;
            }
        }
        return false;
    }

    private function changeToObject($result_db) {
        $object_lista = array();
        foreach ($result_db as $key => $value) {
            $object = new Cliche_dimensao_m();
            $object->id = $value['id'];
            $object->nome = $value['nome'];
            $object->cliche = $value['cliche'];
            $object->valor_servico = $value['valor_servico'];
            $object->valor_cliche = $value['valor_cliche'];
            $object_lista[] = $object;
        }
        return $object_lista;
    }

    public function get_pesonalizado($id_cliche,$colunas){
        $this->db->select($colunas);
        $this->db->where("cliche",$id_cliche);
        $this->db->order_by("nome", "asc");
        return $this->db->get($this->table)->result_array();
    }
}