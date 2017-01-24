<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Personalizado_modelo extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Personalizado_modelo_m');
        $this->load->model('Personalizado_categoria_m');
        init_layout();
        set_layout('titulo', 'Modelos personalizados', FALSE);
        restrito_logado();
    }

    public function index() {
        $data['titulo_painel'] = 'Modelos de Produtos personalizados';
        $data['personalizado_categoria'] = $this->Personalizado_categoria_m->get_list();
        set_layout('conteudo', load_content('personalizado_modelo/lista', $data));
        load_layout();
    }

    public function ajax_list() {
        $list = $this->Personalizado_modelo_m->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array(
                'DT_RowId' => $item->pm_id,
                'id' => $item->pm_id,
                'personalizado_categoria' => $item->pc_nome,
                'nome' => $item->pm_nome,
                'codigo' => $item->pm_codigo,
                'formato' => $item->pm_formato,
                'descricao' => $item->pm_descricao,
                'valor' => $item->pm_valor,
                );
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Personalizado_modelo_m->count_all(),
            "recordsFiltered" => $this->Personalizado_modelo_m->count_filtered(),
            "data" => $data,
            );
        //output to json format
        print json_encode($output);
    }

    public function ajax_add() {
        $this->_validar_formulario("add");
        $data['status'] = TRUE;
        $objeto = $this->_get_post();
        if ( $this->Personalizado_modelo_m->inserir($objeto)) {
            print json_encode(array("status" => TRUE, 'msg' => 'Registro adicionado com sucesso'));
        } else {
            $data['status'] = FALSE;
            $data['status'] = "Erro ao executar o metodo Ajax_add()";
        }
    }

    public function ajax_edit($id) {
        $data["personalizado_modelo"] = $this->Personalizado_modelo_m->get_by_id($id);
        $data["status"] = TRUE;
        print json_encode($data);
        exit();
    }

    public function ajax_update() {
        $this->_validar_formulario("update");
        $id = $this->input->post('id');
        if ($id) {
            $objeto = $this->_get_post();

            if ($this->Personalizado_modelo_m->editar($objeto)) {
                print json_encode(array("status" => TRUE, 'msg' => 'Registro alterado com sucesso'));
            } else {
                print json_encode(array("status" => FALSE, 'msg' => 'Erro ao executar o metodo Personalizado_modelo_m->editar()'));
            }
        } else {
            print json_encode(array("status" => FALSE, 'msg' => 'ID do registro não foi passado'));
        }
    }

    public function ajax_delete($id) {
        $this->Personalizado_modelo_m->deletar($id);
        print json_encode(array("status" => TRUE, "msg" => "Registro excluido com sucesso"));
    }

    private function _get_post() {
        $objeto = new Personalizado_modelo_m();
        $objeto->id = empty($this->input->post('id')) ? null:$this->input->post('id') ;
        $objeto->codigo = $this->input->post('codigo');
        $objeto->nome = $this->input->post('nome');
        $objeto->personalizado_categoria = $this->input->post('personalizado_categoria');
        $objeto->formato = $this->input->post('formato');
        $objeto->descricao = $this->input->post('descricao');
        $objeto->valor = $this->input->post('valor');
        return $objeto;
    }

    private function _validar_formulario($action) {
        $data = array();
        $data['status'] = TRUE;
        if($action == 'update' && !empty($this->input->post('id'))){
            $object = $this->Personalizado_modelo_m->get_by_id($this->input->post('id'));
            if($this->input->post('codigo') != $object->codigo){
                $is_unique =  '|is_unique[personalizado_modelo.codigo]';
            }else{
                $is_unique =  '';
            }   
        }else{
            $is_unique =  '|is_unique[personalizado_modelo.codigo]';
        }
        $this->form_validation->set_message('is_unique','Já exixte um campo com este código. Dados duplicados não são permitidos.');

        $this->form_validation->set_message('check_white_spaces', 'O código não deve ser uma palavra composta');
        $this->form_validation->set_rules('codigo', 'Código', 'trim|required|max_length[20]|alpha_numeric_spaces|strtolower|callback_check_white_spaces'.$is_unique);
        $this->form_validation->set_rules('nome', 'Nome', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('personalizado_categoria', 'Categoria', 'trim|required');
        $this->form_validation->set_rules('formato', 'Formato', 'trim|required|is_natural_no_zero|max_length[3]');
        $this->form_validation->set_rules('descricao', 'Descrição', 'trim');
        $this->form_validation->set_message('decimal_positive', 'O valor não pode ser menor que 0 (zero)');
        $this->form_validation->set_rules('valor', 'Valor', 'trim|required|callback_decimal_positive');

        if (!$this->form_validation->run()) {
            $data['form_validation'] = $this->form_validation->error_array();
            $data['status'] = FALSE;
            print json_encode($data);
            exit();
        }
    }

    public function decimal_positive($value){
        if($value < 0){
            return false;
        }
        return true;
    }

    public function check_white_spaces($str){
        if(preg_match('/\s/',$str)){
            return false;
        }
        return true;
    }
}