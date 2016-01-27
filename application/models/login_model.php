<?php

 

/**
 *  Responsável pelas funções ligadas aos dados de usuários para CRUD usuário
 *
 * @author Israel Eduardo Zebulon Martins de Souza 12/2014
 */
class Login_model extends CI_Model {

    public function __construct() {
        try {
            parent::__construct();
        } catch (Exception $e) {
            $this->session->set_flashdata('erro', 'Erro acessar base de dados');
            log_message('debug', ' Erro ao validar login ' . $e);
            redirect(base_url());
        }
    }

    /*
     * 
     * @param: @var matricula do usuário
     * return: objeto query do mysqli contendo os dados do usuário
     *      */

    public function obterDadosUsuario($cpf) {

        try {

            $query = $this->db->query("
                                        SELECT 
                            u.id,
                            u.nome,
                            u.cpf,
                            u.status,
                            tp.descricao,
                            us.senha,
                            us.salt,
                            us.id AS idSenha
                        FROM
                            usuarios AS u
                                INNER JOIN
                            tipos_usuarios AS tp ON u.id_tipo_usuario = tp.id
                                        inner join
                            usuario_senhas as us on us.id = u.id    
                        WHERE
                            u.cpf = '$cpf' AND u.status = 'A' and us.status = 'A'");
        } catch (Exception $e) {
            $this->session->set_flashdata('erro', 'Erro ao validar login');
            log_message('debug', ' Erro ao validar login ' . $e);
            redirect(base_url());
        }
        return $query;
    }

    /*
     *  Checa se o usuário está logado 
     *      */

    public function logged() {

        $logged = $this->session->userdata('logado');

        if (!isset($logged) || $logged != true) {
            $this->load->model('mensagens_model', 'mensagens');
            $this->mensagens->defineMesagens(3);
            redirect(base_url());
        }
    }

    

    /*
     * @param usuario e query
     * sem retorno, apenas grava os dados nome, id, matricula na sessao
     */

    public function gravaDadosNaSessao($usuario, $query) {
        try {
            $this->session->set_userdata("logado", 1);
            $this->session->set_userdata("usuario", $usuario);
            foreach ($query->result() as $row) {

                $this->session->set_userdata("nome", $row->nome);
                $this->session->set_userdata("cpf", $row->cpf);
                $this->session->set_userdata("id", $row->id);
                $this->session->set_userdata("tipoUsuario", $row->id_tipo_usuario);
                $this->session->set_userdata("ip", $_SERVER['REMOTE_ADDR']);
                $this->session->set_userdata("descricao", $row->descricao);
                
            }
        } catch (ErrorException $e) {
            $this->session->set_flashdata('erro', 'Erro ao validar login');
            log_message('debug', ' Erro ao validar login ' . $e);
            base_url();
        }
    }

    /*
     * Destruir  a sessão do usuário      
     */

    function logout() {
        $this->session->unset_userdata("logado");
        $this->session->sess_destroy();
    }

    /*
     * Gerar um salt para encriptar a senha do usário     
     *  */

    public function gerarSalt() {
        return base_convert(sha1(uniqid(mt_rand(), TRUE)), 16, 36);
    }

    /*
     * Gerar senha encriptada
     *  */

    public function gerarHash($senha, $salt) {
        $hashSenha = hash('sha512', $senha . $salt);
        for ($i = 0; $i < 10; $i++) {
            $hashSenha = hash('sha512', $hashSenha);
        }
        return $hashSenha;
    }

    /*

     * Verifica a validade da senha
     * @return: boolean TRUE para senha correta     */

    public function validarSenha($senhaEncriptada, $senhaGravada) {

        return $senhaEncriptada == $senhaGravada ? TRUE : FALSE;
    }

    /*

     * Fazer as encriptações e chamar verificação de senha
     *      */

    public function isBValidSenha($senha, $query) {

        foreach ($query->result() as $row) {
            $salt = $row->salt;
            $senhaGravada = $row->senha;
        }
        $senhaEncriptada = self::gerarHash($senha, $salt);
        return self::validarSenha($senhaEncriptada, $senhaGravada);
    }

    /*

     * Gravar a senha atual no banco para o caso do Sisref estar offline     */

    public function gravarSenhaHashBanco($senha, $query) {
        $salt = self::gerarSalt();
        $senhaEncriptada = self::gerarHash($senha, $salt);

        foreach ($query->result() as $row) {
            $id = $row->idSenha;
        }


        try {
            $dados = array(
                'senha' => $senhaEncriptada,
                'salt' => $salt,
                'id_usuario_alteracao' =>  $this->session->userdata("id")
            );

            $this->db->trans_start();
            $this->db->where('id', $id);
            $this->db->update('usuario_senhas', $dados);
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                log_message('debug', ' Erro ao persistir senha no banco ' . $id);
            }
        } catch (Exception $e) {
            $this->session->set_flashdata('erro', 'Erro ao persistir senha no banco' . $e);
            redirect(base_url());
        }
    }

}
