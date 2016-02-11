<?php

/*
  GPREC - Gestão de Processos de Recursos
 */

/**
 *  Responsável pelas funções ligadas aos dados de usuários para CRUD usuário
 *
 * @author Israel Eduardo Zebulon Martins de Souza 12/2014
 */
class Datas_model extends CI_Model {

    public function __construct() {

        parent::__construct();
        try {
            #$this->load->database();
        } catch (Exception $e) {
            log_message('debug', ' Erro ao carregar classe especieModel ' . $e);
            $this->session->set_flashdata('erro', 'Erro ao carregar dados -  Contate Suporte');
            redirect('sistema/inicio/');
        }
    }

    /* Checar se d1 é menor que d2 */

    public function calculaDiferencaDatas($d1, $d2) {

        $data1 = new DateTime($d1);
        $data2 = new DateTime($d2);

        return ($data1 > $data2) ? TRUE : FALSE;
    }

     
  
    

    /* Prepara Datas para pesquisa */

    public function preparaCondicaoDatas($data1, $data2, $campo) {


        if (!empty($data1) && !empty($data2)) {
            $dataInicial = DateTime::createFromFormat('d/m/Y', $data1)->format('Y-m-d');
            $dataFinal = DateTime::createFromFormat('d/m/Y', $data2)->format('Y-m-d');

            if ($this->calculaDiferencaDatas($dataInicial, $dataFinal)) {
                $temp = $dataFinal;
                $dataFinal = $dataInicial;
                $dataInicial = $temp;
            }

            return " and ( $campo >= '$dataInicial' AND $campo <= '$dataFinal' ) ";
        }
        return NULL;
    }

     

}
