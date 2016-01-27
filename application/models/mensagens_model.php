<?php

/**
 *
 * @author Israel Eduardo Zebulon Martins de Souza 12/2014
 */
class Mensagens_model extends CI_Model {

    public function __construct() {

        parent::__construct();
    }

    /*
     * @param: 
     * @var : $retorno

     * @return: null
     * 
     * logins e mensagens de alerta do sistema
     *      
     */

    public function defineMesagens($retorno) {
        try {

            switch ($retorno) {
                case 1: {
                        $this->session->set_flashdata('erro', 'CPF não cadastrado');
                        break;
                    }
                case 2: {
                        $this->session->set_flashdata('erro', 'CPF ou Senha inválidos');
                        break;
                    }
                case 3: {
                        $this->session->set_flashdata('logado', 'Necessário realizar novo login');
                        break;
                    }


                default:
                    $this->session->set_flashdata('erro', 'Ação não realizada - contatar suporte');

                    break;
            }
        } catch (Exception $e) {
            $this->session->set_flashdata('erro', 'Erro ao selecionar mensagem para cadastro de usuário');
            log_message('debug', 'Erro ao selecionar mensagem para cadastro de usuário' . $e);
            redirect('usuario/listar/');
        }
    }

}
