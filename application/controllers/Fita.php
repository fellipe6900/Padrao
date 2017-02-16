<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Fita extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Fita_m');
        $this->load->model('Fita_laco_m');
        $this->load->model('Fita_material_m');
        init_layout();
        set_layout('titulo', 'Fita', FALSE);
        restrito_logado();
    }

    public function index() {
        set_layout('conteudo', load_content('fita/lista', ""));
        load_layout();
    }

    public function ajax_list() {
        $list = $this->Fita_m->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array(
                'DT_RowId' => $item->f_id,
                'id' => $item->f_id,
                'fita_laco' => $item->fl_nome,
                'fita_material' => $item->fm_nome,
                'valor_03mm' => $item->valor_03mm,
                'valor_07mm' => $item->valor_07mm,
                'valor_10mm' => $item->valor_10mm,
                'valor_15mm' => $item->valor_15mm,
                'valor_22mm' => $item->valor_22mm,
                'valor_38mm' => $item->valor_38mm,
                'valor_50mm' => $item->valor_50mm,
                'valor_70mm' => $item->valor_70mm,
                );
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Fita_m->count_all(),
            "recordsFiltered" => $this->Fita_m->count_filtered(),
            "data" => $data,
            );
        //output to json format
        print json_encode($output);
    }

    public function ajax_add() {
        $this->validar_formulario("add");
        $data['status'] = TRUE;
        $objeto = $this->get_post();
        if ( $this->Fita_m->inserir($objeto)) {
            print json_encode(array("status" => TRUE, 'msg' => 'Registro adicionado com sucesso'));
        } else {
            $data['status'] = FALSE;
            $data['status'] = "Erro ao executar o metodo Ajax_add()";
        }
    }

    public function ajax_edit($id) {
        $data["fita"] = $this->Fita_m->get_by_id($id);
        $data["status"] = TRUE;
        print json_encode($data);
        exit();
    }

    public function ajax_update() {
        $this->validar_formulario("update");
        $id = $this->input->post('id');
        if ($id) {
            $objeto = $this->get_post();

            if ($this->Fita_m->editar($objeto)) {
                print json_encode(array("status" => TRUE, 'msg' => 'Registro alterado com sucesso'));
            } else {
                print json_encode(array("status" => FALSE, 'msg' => 'Erro ao executar o metodo Fita_m->editar()'));
            }
        } else {
            print json_encode(array("status" => FALSE, 'msg' => 'ID do registro não foi passado'));
        }
    }

    public function ajax_delete($id) {
        $this->Fita_m->deletar($id);
        print json_encode(array("status" => TRUE, "msg" => "Registro excluido com sucesso"));
    }

    private function get_post() {
        $objeto = new Fita_m();
        $objeto->id = empty($this->input->post('id')) ? null:$this->input->post('id') ;
        $objeto->fita_laco = $this->input->post('fita_laco');
        $objeto->fita_material = $this->input->post('fita_material');
        $objeto->valor_03mm = $this->input->post('valor_03mm');
        $objeto->valor_07mm = $this->input->post('valor_07mm');
        $objeto->valor_10mm = $this->input->post('valor_10mm');
        $objeto->valor_15mm = $this->input->post('valor_15mm');
        $objeto->valor_22mm = $this->input->post('valor_22mm');
        $objeto->valor_38mm = $this->input->post('valor_38mm');
        $objeto->valor_50mm = $this->input->post('valor_50mm');
        $objeto->valor_70mm = $this->input->post('valor_70mm');
        return $objeto;
    }

    public function ajax_get_personalizado($id_material){
        $arr = array();
        $colunas = "fita.id as id,fl.nome as nome,fita.valor_03mm,fita.valor_07mm,fita.valor_10mm,fita.valor_15mm,fita.valor_22mm,fita.valor_38mm,fita.valor_50mm,fita.valor_70mm";
        $arr = $this->Fita_m->get_pesonalizado($id_material,$colunas);
        print json_encode($arr);
    }

    private function validar_formulario($action) {
        $data = array();
        $data['status'] = TRUE;

        $this->form_validation->set_rules('fita_laco', 'Fita laco', 'trim|required');
        $this->form_validation->set_rules('fita_material', 'Fita material', 'trim|required');
        $this->form_validation->set_message('decimal_positive', 'O valor não pode ser menor que 0 (zero)');
        $this->form_validation->set_rules('valor_03mm', 'valor_03mm', 'trim|required|decimal_positive');
        $this->form_validation->set_rules('valor_07mm', 'valor_07mm', 'trim|required|decimal_positive');
        $this->form_validation->set_rules('valor_10mm', 'valor_10mm', 'trim|required|decimal_positive');
        $this->form_validation->set_rules('valor_15mm', 'valor_15mm', 'trim|required|decimal_positive');
        $this->form_validation->set_rules('valor_22mm', 'valor_22mm', 'trim|required|decimal_positive');
        $this->form_validation->set_rules('valor_38mm', 'valor_38mm', 'trim|required|decimal_positive');
        $this->form_validation->set_rules('valor_50mm', 'valor_50mm', 'trim|required|decimal_positive');
        $this->form_validation->set_rules('valor_70mm', 'valor_70mm', 'trim|required|decimal_positive');

        if (!$this->form_validation->run()) {
            $data['form_validation'] = $this->form_validation->error_array();
            $data['status'] = FALSE;
            print json_encode($data);
            exit();
        }
    }
}