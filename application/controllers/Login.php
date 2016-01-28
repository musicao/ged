<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Login extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
         $this->load->model('login_model', 'login');
    }

    public function index() {
       
        add_css(array('login.css'));
        add_js(array('CPF.js', 'login_validacao.js'));
        $this->load->view('template/html.php', array('load_css' => put_css_headers(), 'load_js' => put_js_headers()));
        $this->load->view('logar');
        $this->load->view('template/footer.php');
    }

    public function logar() {
       
       
        $config = array(
            array(
                'field' => 'cpf',
                'label' => 'CPF',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|required'
            )
        );

 
        $this->form_validation->set_rules($config);
        
       
        if ($this->form_validation->run() == FALSE) {
           
             redirect(base_url());
        } else {
             
            $voluntario = preg_replace("/[^0-9]/", "", htmlentities($this->input->post("cpf"), ENT_QUOTES));
            $senha = $this->input->post("password");
            
            $query = $this->login->obterDadosUsuario($voluntario);

            $this->load->model('mensagens_model','mensagens');

            if ($query->num_rows() > 0) {
   
                if ($this->login->isBValidSenha($senha, $query)) {
                    $this->login->gravaDadosNaSessao($voluntario, $query);
                    $this->login->gravarSenhaHashBanco($senha, $query);
                    \redirect(base_url('sistema/inicio'));
                } else {
                    
                    $this->mensagens->defineMesagens(2);
                }
            } else {
                
                $this->mensagens->defineMesagens(1);
            }
  
            redirect(base_url());
        }
    }

    public function logout() {
        $this->load->model('login_model','login');
        $this->login->logout();
       
        redirect(base_url());
    }

}
