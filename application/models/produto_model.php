<?php

/**
 *   
 *
 * @author Israel Eduardo Zebulon Martins de Souza 02/2016
 */
class Produto_model extends CI_Model {

    public function __construct() {
        try {
            parent::__construct();
        } catch (Exception $e) {
            $this->session->set_flashdata('erro', 'Erro acessar base de dados');
            log_message('debug', ' Erro ao cadastrar produto ' . $e);
            redirect(base_url());
        }
    }

    public function inserir($nome, $minimo, $maximo, $obs) {
        $dados = array(
            "nome" => $nome,
            "estoque_minimo" => $minimo,
            "estoque_maximo" => $maximo,
            "descricao" => $obs,
            "id_voluntario_cadastro" => $this->session->userdata('id')
        );

        $this->db->trans_start();
        $this->db->insert('produtos', $dados);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            log_message('debug', "Erro ao cadastrar produto $nome");
            return 4;
        }

        return 5;
    }

    public function desativarProduto($id) {

        try {

            $dados = array(
                'status' => 'I',
                'id_voluntario_cadastro' => $this->session->userdata('id')
            );

            $this->db->trans_start();
            $this->db->where('id', $id);
            $this->db->update('produtos', $dados);
            $retorno = $this->db->affected_rows();
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                log_message('debug', ' Erro ao excluir produto ' . $id);
            }
            return $retorno;
        } catch (Exception $e) {
            $this->session->set_flashdata('erro', 'Erro ao Excluir produto');
            log_message('debug', ' Erro ao excluir produto ' . $e);
            redirect('produto/listar');
        }
    }

    public function listagem() {
        return $this->db->query("SELECT * FROM  produtos where status = 'A' order by nome ASC");
    }

    public function buscarProdutoPorId($id) {
        return $this->db->query("SELECT id,status, nome, estoque_minimo as minimo, "
                        . "estoque_maximo as maximo, descricao as obs FROM  produtos where id=$id");
    }

    public function atualizar($nome, $minimo, $maximo, $obs, $id) {
        $dados = array(
            "nome" => $nome,
            "estoque_minimo" => $minimo,
            "estoque_maximo" => $maximo,
            "descricao" => $obs,
            "id_voluntario_cadastro" => $this->session->userdata('id')
        );

        if ($this->session->userdata('tipoVoluntario') != 1) {
            return 7;
        } else {
            $this->db->trans_start();
            $this->db->where('id', $id);
            $this->db->update('produtos', $dados);

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                log_message('debug', "Erro ao cadastrar produto $nome");
                return 7;
            }
        }



        return 6;
    }

}
