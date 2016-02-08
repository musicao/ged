<?php

/**
 *   
 *
 * @author Israel Eduardo Zebulon Martins de Souza 02/2016
 */
class Usuario_model extends CI_Model {

    public function __construct() {
        try {
            parent::__construct();
        } catch (Exception $e) {
            $this->session->set_flashdata('erro', 'Erro acessar base de dados');
            log_message('debug', ' Erro ao cadastrar voluntario ' . $e);
            redirect(base_url());
        }
    }

    public function bolChecaCpfCadastrado($cpf, $requisicao = NULL, $id = NULL) {
        try {
            $str = NULL;
            if ($id) {
                $str = " and id != $id";
            }
            $query = $this->db->query(" 
                SELECT * from usuarios where cpf = '$cpf'
                 and status = 'A' $str limit 1");

            if ($requisicao) {
                if ($query->num_rows() > 0) {
                    echo json_encode([
                        'existe' => false,
                        'data' => TRUE
                    ]);
                } else {
                    echo json_encode([
                        'existe' => false
                    ]);
                }
            } else {
                if ($query->num_rows() > 0) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            }
        } catch (Exception $e) {
            log_message('debug', ' Erro em bolChecaCpfCadastrado ' . $e);
            $this->session->set_flashdata('erro', 'Erro ao verificar existência'
                    . 'do cpf -  Contate Suporte');
            redirect('usuarios/listar/');
        }
    }

    public function inserir($nome, $cpf, $selCidade,$telefone) {

        if ($this->bolChecaCpfCadastrado($cpf, NULL)) {
            return 8;
        }
        $dados = array(
            "nome" => $nome,
            "cpf" => $cpf,
            "cod_cidades" => $selCidade,
            "telefone" => $telefone,
            "id_voluntario_cadastro" => $this->session->userdata('id')
        );

        $this->db->trans_start();
        $this->db->insert('usuarios', $dados);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            log_message('debug', "Erro ao cadastrar usuario $nome");
            return 4;
        }

        return 5;
    }

    public function desativarUsuario($id) {

        try {

            $dados = array(
                'status' => 'I',
                'id_voluntario_cadastro' => $this->session->userdata('id')
            );

            $this->db->trans_start();
            $this->db->where('id', $id);
            $this->db->update('usuarios', $dados);
            $retorno = $this->db->affected_rows();

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                log_message('debug', ' Erro ao excluir usuario ' . $id);
            }
            return $retorno;
        } catch (Exception $e) {
            $this->session->set_flashdata('erro', 'Erro ao Excluir usuario');
            log_message('debug', ' Erro ao excluir usuario ' . $e);
            redirect('usuario/listar');
        }
    }

    public function listagem() {
        return $this->db->query("SELECT * FROM  v_usuarios  order by nome ASC");
    }

    public function buscarUsuarioPorId($id) {
        return $this->db->query("SELECT *  FROM  v_usuarios where id=$id");
    }

    public function atualizar($nome, $cpf, $selCidade,$telefone,$id) {

        if ($this->bolChecaCpfCadastrado($cpf, NULL, $id)) {
            return 8;
        }
        $dados = array(
            "nome" => $nome,
            "cpf" => $cpf,
            "cod_cidades" => $selCidade,
            "telefone" => $telefone,
            "id_voluntario_cadastro" => $this->session->userdata('id')
        );

        $this->db->trans_start();
        $this->db->where('id', $id);
        $this->db->update('usuarios', $dados);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            log_message('debug', "Erro ao editar usuario $nome");
            return 4;
        }

        return 5;
    }

    public function listarEstados() {

        return $this->db->query("select cod_estados as id, sigla
 from estados  order by sigla ASC");
    }

    public function listarCidades($id, $respostaAjax) {

        try {
            $str = NULL;
            if ($id) {
                $str = " and estados_cod_estados = $id ";
            }else{
                $str = " and 1=2"; //apenas para forçar devolver obj vazio
            }

            $cidades = $this->db->query(
                    " SELECT 
                            cod_cidades AS id, nome
                        FROM
                            cidades
                        WHERE
                            1 = 1 $str
                        ORDER BY nome ASC"
            );
             

            if ($respostaAjax) {
                $data = array();
                 
                foreach ($cidades->result() as $row) {
                    $cidades = array("id" => $row->id, "nome" => $row->nome);
                    array_push($data, $cidades);
                }
                echo json_encode([
                    'erro' => false,
                    'data' => $data
                ]);die;
            } else {
                return $cidades;
            }
        } catch (Exception $e) {
            log_message('debug', ' Erro ao carregar função listarcidades ' . $e);
            $this->session->set_flashdata('erro', 'Erro ao carregar dados -  Contate Suporte');
            redirect('usuario/listar/');
        }
    }

}
