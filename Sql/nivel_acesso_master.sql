-- Migração: adiciona o nível "master" e remove a dependência do
-- código de administrador no cadastro (agora o controle de quem é
-- admin é feito só pelo painel de usuários, por um master já logado).
--
-- Rode este script no banco `pindaeco` já existente.
-- (Se você recriar o banco do zero a partir de Sql/migrations.sql,
-- ele já vem com o enum atualizado e não precisa rodar isso.)

ALTER TABLE `usuarios`
  MODIFY `tipo_usuario` ENUM('usuario','admin','master') NOT NULL DEFAULT 'usuario';

-- Define a primeira conta master do sistema.
-- Troque o e-mail abaixo pelo e-mail da conta que deve ser a master.
-- (Por padrão, promove a conta que já era admin nos dados de exemplo.)
UPDATE `usuarios`
SET `tipo_usuario` = 'master'
WHERE `email` = 'kaiobarbosa0694@gmail.com';
