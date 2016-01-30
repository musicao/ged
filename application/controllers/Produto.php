<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Produto extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('login_model', 'login');
         $this->login->logged();
    }
    
    
    public function listar() {
        
        $this->load->view('template/html.php');
        $this->load->view('template/header.php');
        $this->load->view('template/navbar.php');
        $this->load->view('template/principal.php');
        $this->load->view('telaInicial.php');
        $this->load->view('produtos/listar.php');
        $this->load->view('template/footer.php');
        
    }
    
    public function inserir() {
        
        $this->load->view('template/html.php');
        $this->load->view('template/header.php');
        $this->load->view('template/navbar.php');
        $this->load->view('template/principal.php');
        $this->load->view('telaInicial.php');
        $this->load->view('produtos/inserir.php');
        $this->load->view('template/footer.php');
        
    }
    
    

}
