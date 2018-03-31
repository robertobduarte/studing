#MYSQL

CREATE SCHEMA `base` DEFAULT CHARACTER SET utf8 ;

use base;


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
