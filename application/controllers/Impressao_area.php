<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Impressao_area extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Impressao_area_m');
        init_layout();
        set_layout('titulo', 'Impressões área', FALSE);
        restrito_logado();
    }

    public function index() {
        $data['titulo_painel'] = 'Impressões área';
        set_layout('conteudo', load_content('impressao_area/lista', $data));
        load_layout();
    }

    public function ajax_list() {
        $list = $this->Impressao_area_m->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array(
                'DT_RowId' => $item->id,
                'id' => $item->id,
                'nome' => $item->nome,
                'descricao' => $item->descricao,
                );
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Impressao_area_m->count_all(),
            "recordsFiltered" => $this->Impressao_area_m->count_filtered(),
            "data" => $data,
            );
        //output to json format
        print json_encode($output);
    }

    public function ajax_add() {
        $this->validar_formulario();
        $data['status'] = TRUE;
        $objeto = $this->get_post();
        if ( $this->Impressao_area_m->inserir($objeto)) {
            print json_encode(array("status" => TRUE, 'msg' => 'Registro adicionado com sucesso'));
        } else {
            $data['status'] = FALSE;
            $data['status'] = "Erro ao executar o metodo Ajax_add()";
        }
    }

    public function ajax_edit($id) {
        $data["impressao_area"] = $this->Impressao_area_m->get_by_id($id);
        $data["status"] = TRUE;
        print json_encode($data);
        exit();
    }

    public function ajax_update() {
        $data['status'] = FALSE;
        $this->validar_formulario();
        $id = $this->input->post('id');
        if ($id) {
            $objeto = $this->get_post();

            if ($this->Impressao_area_m->editar($objeto)) {
                $data['status'] = TRUE;
            }
        }
        print json_encode($data);
    }

    public function ajax_delete($id) {
        $this->Impressao_area_m->deletar($id);
        print json_encode(array("status" => TRUE, "msg" => "Registro excluido com sucesso"));
    }

    public function ajax_get_personalizado(){
        $arr = array();
        $arr = $this->Impressao_area_m->get_pesonalizado("id, nome");
        print json_encode($arr);
    }

    private function get_post() {
        $objeto = new Impressao_area_m();
        $objeto->id = empty($this->input->post('id')) ? null:$this->input->post('id') ;
        $objeto->nome = $this->input->post('nome');
        $objeto->descricao = $this->input->post('descricao');
        return $objeto;
    }

    private function validar_formulario() {
        $data = array();
        $data['status'] = TRUE;

        $this->form_validation->set_rules('nome', 'Nome', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('descricao', 'Descrição', 'trim');

        if (!$this->form_validation->run()) {
            $data['form_validation'] = $this->form_validation->error_array();
            $data['status'] = FALSE;
            print json_encode($data);
            exit();
        }
    }
}