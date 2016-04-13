<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Relatorio extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('login_model', 'login');
        $this->login->logged();
        $this->login->logged();
        $this->load->model('produto_model', 'produtos');
    }

    public function relatorioMensal()
    {


        $config = array(
            array(
                'field' => 'ano',
                'label' => 'ANO',
                'rules' => 'trim|required|validarAno'
            ),
            array(
                'field' => 'selMes',
                'label' => 'Mês',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'selProduto',
                'label' => 'Nome do Produto',
                'rules' => 'trim|required'
            ),
        );


        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div><p class="text-danger">', '</p></div>');

        if ($this->form_validation->run() == false) {


            $produtos = $this->produtos->listagemGeral();

            $this->load->view('template/html.php');
            $this->load->view('template/header.php');
            $this->load->view('template/navbar.php');
            $this->load->view('template/principal.php');
            $this->load->view('relatorio/requisitos.php', array('produtos' => $produtos));
            $this->load->view('template/footer.php');
        } else {
            $this->load->view('template/html.php');
            $this->load->view('template/header.php');
            $this->load->view('template/navbar.php');
            $this->load->view('template/principal.php');


            $ano = $this->input->post('ano');
            $mes = $this->data->nomeMes($this->input->post('selMes'));
            $produto = $this->input->post('selProduto');
            $nomeProduto = $this->produtos->obterNome($produto);
            $total = array();
            $cont = 0;


            for ($dia=1; $dia <= 31;$dia++){
                $dados[$mes][$dia] = $this->produtos->apuracaoDadosMes($ano,$this->input->post('selMes'),$dia,1,$produto);
                $cont += $dados[$mes][$dia];
            }

          $total[$mes] = $cont;

            $this->load->view('relatorio/historico_mensal.php', array("dados" => $dados,"nomeProduto"=>strtoupper($nomeProduto),"mes"=>$mes,"ano"=>$ano,"total"=>$total,"dia"=>$dia));
            $this->load->view('template/footer.php');
        }
    }
}
