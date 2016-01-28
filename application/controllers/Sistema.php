<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Sistema extends CI_Controller {

     
    public function inicio() {
        $this->load->view('template/html.php');
        $this->load->view('template/header.php');
        $this->load->view('template/navbar.php');
        $this->load->view('template/principal.php');
        $this->load->view('telaInicial.php');
        $this->load->view('template/footer.php');
    }

}
