<?php

 
class Login_model extends CI_Model {

    public function __construct() {
        try {
            parent::__construct();
        } catch (Exception $e) {
            $this->session->set_flashdata('erro', 'Erro acessar base de dados');
            log_message('debug', ' Erro ao validar voluntario ' . $e);
            redirect(base_url());
        }
    }

    public function checaPerfilAdm() {
        return $this->session->userdata('tipoVoluntario') == 1;
    }
    
}
