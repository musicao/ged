ALTER TABLE `cedpv`.`saida_produto` 
ADD COLUMN `tipo_retirada` CHAR(1) NOT NULL DEFAULT '1' COMMENT '1 - normal\n2 - via tela de estoque' AFTER `observacao`;


-- Criar um usuario com o nome SISTEMA colocar no campo cpf o número = 36238374667
-- Na tela de listagem de usuario excluia o usuario criado.
-- acesse a tabela usuario no banco de dados veja qual o ID que o sistema deu pra esse usuario
-- no controlador Estoque.php linha 230
-- Colocar no campo antes da variavel $data_hora, o número do usuario desativado do sistema
-- para os casos de remocao do item do estoqe por um adminitrador


USE `cedpv`;
CREATE  OR REPLACE VIEW `v_retiradas` AS
SELECT p.nome, s.qtde, s.data_saida, s.id_usuario FROM  saida_produto as s inner join produto as p on
s.id_produto = p.id;