<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Estoque extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('login_model', 'login');
        $this->login->logged();
        $this->load->model('estoque_model', 'estoque');
    }

    public function listar() {
 
        $this->load->view('template/html.php');
        $this->load->view('template/header.php');
        $this->load->view('template/navbar.php');
        $this->load->view('template/principal.php');
        $this->load->view('estoques/listar.php');
        $this->load->view('template/footer.php');
    }

    
    public function adicionar() {

        $config = array(
            array(
                'field' => 'selProduto',
                'label' => 'Nome do Produto',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'quantidade',
                'label' => 'Quantidade',
                'rules' => 'trim|required|greater_than[0]'
            ) 
        );


        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div><p class="text-danger">', '</p></div>');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('template/html.php');
            $this->load->view('template/header.php');
            $this->load->view('template/navbar.php');
            $this->load->view('template/principal.php');
            $this->load->model('produto_model', 'produtos');
            $produtos = $this->produtos->listagem();

            $this->load->view('estoques/inserir.php', array('produtos'=>$produtos));
            $this->load->view('template/footer.php');
        } else {

            $idProduto = $this->input->post('selProduto');
            $quantidade = $this->input->post('quantidade');
            $obs =  $this->input->post('obs');
            $retorno = $this->estoque->inserir($idProduto,$quantidade,$obs);

            $this->load->model('mensagens_model', 'mensagens');
            $this->mensagens->defineMesagens($retorno);

            redirect('estoque/listar');
        }
    }

    public function editar($id) {

        $config = array(
            array(
                'field' => 'nomeProduto',
                'label' => 'Nome do Produto',
                'rules' => 'trim|required|min_length[5]'
            ),
            array(
                'field' => 'minimo',
                'label' => 'Quantidade mínima',
                'rules' => 'trim|required|integer'
            ),
            array(
                'field' => 'maximo',
                'label' => 'Quantidade máxima',
                'rules' => 'trim|required|integer'
            ),
            array(
                'field' => 'obs',
                'label' => 'Observações',
                'rules' => 'encode_php_tags'
            )
        );


        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div><p class="text-danger">', '</p></div>');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('template/html.php');
            $this->load->view('template/header.php');
            $this->load->view('template/navbar.php');
            $this->load->view('template/principal.php');
            $query = $this->produto->buscarProdutoPorId($id);

            $this->load->view('produtos/editar.php', array("produto" => $query->row()));
            $this->load->view('template/footer.php');
        } else {

            $nome = $this->input->post('nomeProduto');
            $minimo = $this->input->post('minimo');
            $maximo = $this->input->post('maximo');
            $obs = htmlentities($this->input->post('obs'), ENT_QUOTES);


            $retorno = $this->produto->atualizar($nome, $minimo, $maximo, $obs, $id);



            $this->load->model('mensagens_model', 'mensagens');
            $this->mensagens->defineMesagens($retorno);

            redirect('produto/listar');
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

            $valor = json_decode($this->input->post('produto'));
            $retorno = $this->produto->desativarProduto($valor->id);


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

    public function relacionar() {


        $query = $this->produto->listagem();

        return json_encode($query->result());
    }

}
