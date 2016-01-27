<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

   
    public function index() {
        
        add_css(array('login.css'));
        add_js(array('CPF.js','login_validacao.js'));
        $this->load->view('template/header.php', array('load_css' => put_css_headers(), 'load_js' => put_js_headers()));
        $this->load->view('logar');
        $this->load->view('template/footer.php');
    }

}
