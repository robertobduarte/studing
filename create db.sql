#MYSQL

CREATE SCHEMA `studing` DEFAULT CHARACTER SET utf8 ;

use studing;


create table usuario (
	id int auto_increment not null primary key,
	usuario varchar(200) not null,
	perfil char(5) not null,
	pessoa int not null,
	password varchar(100)
);


create table pessoa (
	id int auto_increment not null primary key,
	nome varchar(200) not null,
	email varchar(200),
	cpf varchar(15),
	telefone varchar(30),
	celular varchar(30),
	endereco varchar(200),
	bairro varchar(100),
	cep varchar(15),
	estado varchar(100)
)


CREATE TABLE objetivo (
    id INT auto_increment NOT NULL primary key,
    nome VARCHAR(200) NOT NULL,
    descricao TEXT,
    objetivo_tipo INT,
    parent INT,
    leaf char(1) NOT NULL DEFAULT 'S',
    ordem int default null
);


CREATE TABLE objetivo_tipo (
    id INT NOT NULL primary key auto_increment,
    nome VARCHAR(200) NOT NULL
);

ALTER TABLE objetivo_tipo
CHANGE COLUMN id id INT(11) NOT NULL AUTO_INCREMENT;


#criação de objetivos
insert into objetivo (nome, descricao, objetivo_tipo, parent, leaf) values
('4º Ano', 'Reforçar os conhecimentos adquiridos no quarto ano no colégio Gensa - Gravataí', 1, null, 'N'),
('1 Trimestre', 'Reforçar os conhecimentos adquiridos no 1º trimestre', 3, 1, 'S'),
('2 Trimestre', 'Reforçar os conhecimentos adquiridos no 2º trimestre', 3, 1, 'S'),
('3 Trimestre', 'Reforçar os conhecimentos adquiridos no 3º trimestre', 3, 1, 'S');

#criação de objetivo tipo
insert into objetivo_tipo (nome) values
('Ano letivo'), 
('Semestre'),
('Trimestre'),
('Bimestre');