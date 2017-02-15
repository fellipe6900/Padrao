<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Fita_m extends CI_Model {

    var $id;
    var $fita_laco;
    var $fita_material;
    var $valor_03mm;
    var $valor_07mm;
    var $valor_10mm;
    var $valor_15mm;
    var $valor_22mm;
    var $valor_38mm;
    var $valor_50mm;
    var $valor_70mm;
    // Ajax 
    var $table = 'fita as f';
    var $column_order = array('f.id', 'fl.nome', 'fm.nome', 'f.valor_03mm','f.valor_07mm','f.valor_10mm','f.valor_15mm','f.valor_22mm','f.valor_38mm','f.valor_50mm','f.valor_70mm');
    var $column_search = array('fl.nome', 'fm.nome');
    var $order = array('f.id'=>'asc');

    private function get_datatables_query() {
        $this->db->select('
            f.id as f_id,
            f.fita_laco as f_fita_laco,
            f.fita_material as f_fita_material,
            CONCAT("R$ ", format(f.valor_03mm,2,"pt_BR")) as valor_03mm,
            CONCAT("R$ ", format(f.valor_07mm,2,"pt_BR")) as valor_07mm,
            CONCAT("R$ ", format(f.valor_10mm,2,"pt_BR")) as valor_10mm,
            CONCAT("R$ ", format(f.valor_15mm,2,"pt_BR")) as valor_15mm,
            CONCAT("R$ ", format(f.valor_22mm,2,"pt_BR")) as valor_22mm,
            CONCAT("R$ ", format(f.valor_38mm,2,"pt_BR")) as valor_38mm,
            CONCAT("R$ ", format(f.valor_50mm,2,"pt_BR")) as valor_50mm,
            CONCAT("R$ ", format(f.valor_70mm,2,"pt_BR")) as valor_70mm,
            ');
        $this->db->select('
            fl.nome as fl_nome,
            fm.nome as fm_nome
            ');
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
    
    public function get_datatables() {
        $this->get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $this->__join();
        $query = $this->db->get();
        
        return $query->result();
    }
    
    public function count_filtered() {
        $this->get_datatables_query();
        $this->__join();
        $query = $this->db->get();
        return $query->num_rows();
    }

    private function __join(){
        $this->db->join('fita_laco as fl', 'f.fita_laco = fl.id', 'left');
        $this->db->join('fita_material as fm', 'f.fita_material = fm.id', 'left');
    }
    
    public function count_all() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_by_id($id){
        $this->db->where('id', $id);
        $this->db->limit(1);
        $result = $this->db->get('fita');
        if($result->num_rows() > 0){
            $result =  $this->Fita_m->changeToObject($result->result_array());
            return $result[0];
        }
        return false;
    }

    public function get_list() {
        $result = $this->db->get('fita');
        return $this->Fita_m->changeToObject($result->result_array());
    }

    public function inserir($objeto) {
        if (!empty($objeto)) {
            $dados = $this->get_dados($objeto);
            if ($this->db->insert('fita', $dados)) {
                return $this->db->insert_id();
            }
        }
        return false;
    }

    public function editar($objeto) {
        if (!empty($objeto->id)) {
            $dados = $this->get_dados($objeto);
            $this->db->where('id', $objeto->id);
            if ($this->db->update('fita', $dados)) {
                return true;
            }
        }
        return false;
    }

    private function get_dados($objeto){
        $dados = array(
            'id' => $objeto->id,
            'fita_laco' => $objeto->fita_laco,
            'fita_material' => $objeto->fita_material,
            'valor_03mm' => str_replace(',', '.', $objeto->valor_03mm),
            'valor_07mm' => str_replace(',', '.', $objeto->valor_07mm),
            'valor_10mm' => str_replace(',', '.', $objeto->valor_10mm),
            'valor_15mm' => str_replace(',', '.', $objeto->valor_15mm),
            'valor_22mm' => str_replace(',', '.', $objeto->valor_22mm),
            'valor_38mm' => str_replace(',', '.', $objeto->valor_38mm),
            'valor_50mm' => str_replace(',', '.', $objeto->valor_50mm),
            'valor_70mm' => str_replace(',', '.', $objeto->valor_70mm),
            );
        return $dados;
    }

    public function deletar($id) {
        if (!empty($id)) {
            $this->db->where('id', $id);
            if ($this->db->delete('fita')) {
                return true;
            }
        }
        return false;
    }

    private function changeToObject($result_db) {
        $object_lista = array();
        foreach ($result_db as $key => $value) {
            $object = new Fita_m();
            $object->id = $value['id'];
            $object->valor_03mm = $value['valor_03mm'];
            $object->valor_07mm = $value['valor_07mm'];
            $object->valor_10mm = $value['valor_10mm'];
            $object->valor_15mm = $value['valor_15mm'];
            $object->valor_22mm = $value['valor_22mm'];
            $object->valor_38mm = $value['valor_38mm'];
            $object->valor_50mm = $value['valor_50mm'];
            $object->valor_70mm = $value['valor_70mm'];
            $object->fita_laco = $this->Fita_laco_m->get_by_id($value['fita_laco']);
            $object->fita_material = $this->Fita_material_m->get_by_id($value['fita_material']);
            $object_lista[] = $object;
        }
        return $object_lista;
    }

    public function get_espessura_json(){
        $arr = array(
            '3'=>$this->valor_03mm,
            '7'=>$this->valor_07mm,
            '10'=>$this->valor_10mm,
            '15'=>$this->valor_15mm,
            '22'=>$this->valor_22mm,
            '38'=>$this->valor_38mm,
            '50'=>$this->valor_50mm,
            '70'=>$this->valor_70mm,
            );
        return json_encode($arr);
    }

    public function get_pesonalizado($id_material,$colunas){
        $this->db->select($colunas);
        $this->db->join('fita_laco as fl', 'fita.fita_laco = fl.id', 'left');
        $this->db->where("fita_material",$id_material);
        $this->db->from("fita");
        $this->db->order_by("fl.nome", "asc");
        return $this->db->get()->result_array();
    }
}