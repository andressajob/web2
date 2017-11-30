/* Drop Tables */
DROP TABLE IF EXISTS horario_veiculo;
DROP TABLE IF EXISTS pagamento;
DROP TABLE IF EXISTS ticket;
DROP TABLE IF EXISTS box;
DROP TABLE IF EXISTS veiculo;
DROP TABLE IF EXISTS cliente;
DROP TABLE IF EXISTS incidente;
DROP TABLE IF EXISTS funcionario;
DROP TABLE IF EXISTS estacionamento;
DROP TABLE IF EXISTS endereco;
DROP TABLE IF EXISTS cidade;
DROP TABLE IF EXISTS estado;
DROP TABLE IF EXISTS modelo;
DROP TABLE IF EXISTS marca;
DROP TABLE IF EXISTS pais;
DROP TABLE IF EXISTS tipo;
DROP TABLE IF EXISTS usuario;
/* Create Tables */
CREATE TABLE box
(
id_box serial NOT NULL,
descricao varchar NOT NULL,
PRIMARY KEY (id_box)
) WITHOUT OIDS;
CREATE TABLE cidade
(
id_cidade serial NOT NULL,
nome_cidade varchar NOT NULL,
id_estado int NOT NULL,
PRIMARY KEY (id_cidade)
) WITHOUT OIDS;
CREATE TABLE cliente
(
id_cliente int NOT NULL,
nome_cliente varchar NOT NULL,
cpf bigint NOT NULL UNIQUE,
contato bigint NOT NULL UNIQUE,
id_endereco int NOT NULL,
id_tipo int NOT NULL,
PRIMARY KEY (id_cliente)
) WITHOUT OIDS;
CREATE TABLE endereco
(
id_endereco serial NOT NULL,
logradouro varchar NOT NULL,
numero int NOT NULL,
bairro varchar NOT NULL,
cep bigint NOT NULL,
id_cidade int NOT NULL,
PRIMARY KEY (id_endereco)
) WITHOUT OIDS;
CREATE TABLE estacionamento
(
id_estacionamento serial NOT NULL,
CNPJ bigint NOT NULL UNIQUE,
nome_estacionamento varchar NOT NULL,
vagas int NOT NULL,
id_endereco int NOT NULL,
PRIMARY KEY (id_estacionamento)
) WITHOUT OIDS;
CREATE TABLE estado
(
id_estado serial NOT NULL,
nome_estado varchar NOT NULL,
id_pais int NOT NULL,
PRIMARY KEY (id_estado)
) WITHOUT OIDS;
CREATE TABLE funcionario
(
id_funcionario int NOT NULL,
nome_funcionario varchar NOT NULL,
salario double precision NOT NULL,
cpf bigint NOT NULL UNIQUE,
regime_trabalho int NOT NULL,
data_nascimento date NOT NULL,
id_endereco int NOT NULL,
id_estacionamento int NOT NULL,
PRIMARY KEY (id_funcionario)
) WITHOUT OIDS;
CREATE TABLE horario_veiculo
(
id_veiculo int NOT NULL,
id_ticket int NOT NULL,
data_horario_entrada timestamp NOT NULL,
data_horario_saida timestamp,
PRIMARY KEY (id_veiculo, id_ticket, data_horario_entrada)
) WITHOUT OIDS;
CREATE TABLE incidente
(
id_incidente serial NOT NULL,
data_incidente timestamp NOT NULL,
id_tipo int NOT NULL,
id_funcionario int NOT NULL,
PRIMARY KEY (id_incidente)
) WITHOUT OIDS;
CREATE TABLE marca
(
id_marca serial NOT NULL,
nome_marca varchar NOT NULL,
PRIMARY KEY (id_marca)
) WITHOUT OIDS;
CREATE TABLE modelo
(
id_modelo serial NOT NULL,
nome_modelo varchar NOT NULL,
ano_modelo int NOT NULL,
id_marca int NOT NULL,
PRIMARY KEY (id_modelo)
) WITHOUT OIDS;
CREATE TABLE pagamento
(
id_pagamento int NOT NULL,
forma_pagamento varchar NOT NULL,
valor double precision NOT NULL,
id_ticket int NOT NULL,
PRIMARY KEY (id_pagamento)
) WITHOUT OIDS;
CREATE TABLE pais
(
id_pais serial NOT NULL,
nome_pais varchar NOT NULL,
PRIMARY KEY (id_pais)
) WITHOUT OIDS;
CREATE TABLE ticket
(
id_ticket serial NOT NULL UNIQUE,
pago boolean NOT NULL,
id_estacionamento int NOT NULL,
id_box int NOT NULL,
id_veiculo int NOT NULL,
PRIMARY KEY (id_ticket)
) WITHOUT OIDS;
CREATE TABLE tipo
(
id_tipo serial NOT NULL,
descricao varchar NOT NULL UNIQUE,
PRIMARY KEY (id_tipo)
) WITHOUT OIDS;
CREATE TABLE usuario
(
id_usuario serial NOT NULL,
login varchar NOT NULL UNIQUE,
senha varchar NOT NULL,
email varchar NOT NULL UNIQUE,
PRIMARY KEY (id_usuario)
) WITHOUT OIDS;
CREATE TABLE veiculo
(
id_veiculo serial NOT NULL,
placa_veiculo varchar(8) NOT NULL UNIQUE,
cor varchar NOT NULL,
id_modelo int NOT NULL,
id_cliente int NOT NULL,
PRIMARY KEY (id_veiculo)
) WITHOUT OIDS;
/* Create Foreign Keys */
ALTER TABLE ticket
ADD FOREIGN KEY (id_box)
REFERENCES box (id_box)
ON UPDATE RESTRICT
ON DELETE RESTRICT
;
ALTER TABLE endereco
ADD FOREIGN KEY (id_cidade)
REFERENCES cidade (id_cidade)
ON UPDATE RESTRICT
ON DELETE RESTRICT
;
ALTER TABLE veiculo
ADD FOREIGN KEY (id_cliente)
REFERENCES cliente (id_cliente)
ON UPDATE RESTRICT
ON DELETE RESTRICT
;
ALTER TABLE cliente
ADD FOREIGN KEY (id_endereco)
REFERENCES endereco (id_endereco)
ON UPDATE RESTRICT
ON DELETE RESTRICT
;
ALTER TABLE estacionamento
ADD FOREIGN KEY (id_endereco)
REFERENCES endereco (id_endereco)
ON UPDATE RESTRICT
ON DELETE RESTRICT
;
ALTER TABLE funcionario
ADD FOREIGN KEY (id_endereco)
REFERENCES endereco (id_endereco)
ON UPDATE RESTRICT
ON DELETE RESTRICT
;
ALTER TABLE funcionario
ADD FOREIGN KEY (id_estacionamento)
REFERENCES estacionamento (id_estacionamento)
ON UPDATE RESTRICT
ON DELETE RESTRICT
;
ALTER TABLE ticket
ADD FOREIGN KEY (id_estacionamento)
REFERENCES estacionamento (id_estacionamento)
ON UPDATE RESTRICT
ON DELETE RESTRICT
;
ALTER TABLE cidade
ADD FOREIGN KEY (id_estado)
REFERENCES estado (id_estado)
ON UPDATE RESTRICT
ON DELETE RESTRICT
;
ALTER TABLE incidente
ADD FOREIGN KEY (id_funcionario)
REFERENCES funcionario (id_funcionario)
ON UPDATE RESTRICT
ON DELETE RESTRICT
;
ALTER TABLE modelo
ADD FOREIGN KEY (id_marca)
REFERENCES marca (id_marca)
ON UPDATE RESTRICT
ON DELETE RESTRICT
;
ALTER TABLE veiculo
ADD FOREIGN KEY (id_modelo)
REFERENCES modelo (id_modelo)
ON UPDATE RESTRICT
ON DELETE RESTRICT
;
ALTER TABLE estado
ADD FOREIGN KEY (id_pais)
REFERENCES pais (id_pais)
ON UPDATE RESTRICT
ON DELETE RESTRICT
;
ALTER TABLE horario_veiculo
ADD FOREIGN KEY (id_ticket)
REFERENCES ticket (id_ticket)
ON UPDATE RESTRICT
ON DELETE RESTRICT
;
ALTER TABLE pagamento
ADD FOREIGN KEY (id_ticket)
REFERENCES ticket (id_ticket)
ON UPDATE RESTRICT
ON DELETE RESTRICT
;
ALTER TABLE cliente
ADD FOREIGN KEY (id_tipo)
REFERENCES tipo (id_tipo)
ON UPDATE RESTRICT
ON DELETE RESTRICT
;
ALTER TABLE incidente
ADD FOREIGN KEY (id_tipo)
REFERENCES tipo (id_tipo)
ON UPDATE RESTRICT
ON DELETE RESTRICT
;
ALTER TABLE cliente
ADD FOREIGN KEY (id_cliente)
REFERENCES usuario (id_usuario)
ON UPDATE RESTRICT
ON DELETE RESTRICT
;
ALTER TABLE funcionario
ADD FOREIGN KEY (id_funcionario)
REFERENCES usuario (id_usuario)
ON UPDATE RESTRICT
ON DELETE RESTRICT
;
ALTER TABLE horario_veiculo
ADD FOREIGN KEY (id_veiculo)
REFERENCES veiculo (id_veiculo)
ON UPDATE RESTRICT
ON DELETE RESTRICT
;
ALTER TABLE ticket
ADD FOREIGN KEY (id_veiculo)
REFERENCES veiculo (id_veiculo)
ON UPDATE RESTRICT
ON DELETE RESTRICT;