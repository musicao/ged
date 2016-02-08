<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Usuario extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('login_model', 'login');
        $this->login->logged();
        $this->load->model('usuario_model', 'usuario');
    }

    public function listar() {

        $this->load->view('template/html.php');
        $this->load->view('template/header.php');
        $this->load->view('template/navbar.php');
        $this->load->view('template/principal.php');
        $this->load->view('usuarios/listar.php');
        $this->load->view('template/footer.php');
    }

    public function cadastrar() {

        $config = array(
            array(
                'field' => 'nome',
                'label' => 'Nome',
                'rules' => 'trim|required|min_length[5]'
            ),
            array(
                'field' => 'cpf',
                'label' => 'cpf',
                'rules' => 'required|validaCPF'
            ),
            array(
                'field' => 'selEstado',
                'label' => 'Estado',
                'rules' => 'required|estado'
            ),
            array(
                'field' => 'selCidade',
                'label' => 'Cidade',
                'rules' => 'required|cidade'
            )
        );


        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div><p class="text-danger">', '</p></div>');

        if ($this->form_validation->run() == FALSE) {

            $this->load->view('template/html.php');
            $this->load->view('template/header.php');
            $this->load->view('template/navbar.php');
            $this->load->view('template/principal.php');


            $estadoSubmetido = preg_replace("/[^0-9]/", "", htmlentities(filter_input(INPUT_POST, 'selEstado', FILTER_SANITIZE_SPECIAL_CHARS), ENT_QUOTES));
 
            $idEstado = (isset($estadoSubmetido) && $estadoSubmetido > 0)? $estadoSubmetido : 1;

            $estados = $this->usuario->listarEstados();
            
            $cidades = $this->usuario->listarcidades($idEstado, FALSE);

            $this->load->view('usuarios/inserir.php', array('cidades' => $cidades, 'estados' => $estados));
            $this->load->view('template/footer.php');
        } else {

            $nome = $this->input->post('nome');
            $cpf = preg_replace("/[^0-9]/", "", htmlentities($this->input->post('cpf'), ENT_QUOTES));
            $selCidade = $this->input->post('selCidade');
            $telefone = preg_replace("/[^0-9]/", "", htmlentities($this->input->post('telefone'), ENT_QUOTES));
            $retorno = $this->usuario->inserir($nome, $cpf, $selCidade,$telefone);
            $this->load->model('mensagens_model', 'mensagens');
            $this->mensagens->defineMesagens($retorno);

            redirect('usuario/listar');
        }
    }

    public function editar($id) {

        $config = array(
            array(
                'field' => 'nome',
                'label' => 'Nome',
                'rules' => 'trim|required|min_length[5]'
            ),
            array(
                'field' => 'cpf',
                'label' => 'cpf',
                'rules' => 'required|validaCPF'
            ),
            array(
                'field' => 'selEstado',
                'label' => 'Estado',
                'rules' => 'required|estado'
            ),
            array(
                'field' => 'selCidade',
                'label' => 'Cidade',
                'rules' => 'required|cidade'
            )
        );


        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div><p class="text-danger">', '</p></div>');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('template/html.php');
            $this->load->view('template/header.php');
            $this->load->view('template/navbar.php');
            $this->load->view('template/principal.php');
            $query = $this->usuario->buscarUsuarioPorId($id);
            $usuario= $query->row();
            
            
            $estados = $this->usuario->listarEstados();
            $cidades = $this->usuario->listarcidades($usuario->idEstados, FALSE);

            $this->load->view('usuarios/editar.php', array('usuario'=>$query->row(),  'cidades' => $cidades, 'estados' => $estados));
            $this->load->view('template/footer.php');
        } else {


            $nome = $this->input->post('nome');
            $cpf = preg_replace("/[^0-9]/", "", htmlentities($this->input->post('cpf'), ENT_QUOTES));
            $selCidade = $this->input->post('selCidade');
            $telefone = preg_replace("/[^0-9]/", "", htmlentities($this->input->post('telefone'), ENT_QUOTES));
            $retorno = $this->usuario->atualizar($nome, $cpf, $selCidade,$telefone,$id);
            $this->load->model('mensagens_model', 'mensagens');
            $this->mensagens->defineMesagens($retorno); 
            
            redirect('usuario/listar');
        }
    }

    public function deletar() {

        try {

            if (!$this->session->userdata('tipoVoluntario') == 1) {
                echo json_encode(array(
                    'error' => true,
                    'message' => "Ação não autorizada"
                        ), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
                die;
            }

            $valor = json_decode($this->input->post('usuario'));
            $retorno = $this->usuario->desativarUsuario($valor->id);


            if ($retorno) {
                echo json_encode(array(
                    'error' => false,
                    'id' => $valor->id
                        ), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

                die;
            } else {
                throw new Exception("Erro ao deletar");
            }
        } catch (Exception $exc) {
            echo json_encode(array(
                'error' => true,
                'message' => $exc->getMessage()
                    ), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
            die;
        }
    }

    public function listcity() {

        $idEstado = $this->input->post("estado");
        $respostaAjax = $this->input->post("respostaAjax");

        return $this->usuario->listarCidades($idEstado, $respostaAjax);
    }

}
