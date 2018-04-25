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
    dominio int NOT NULL,
    ordem int default null
);


CREATE TABLE objetivo_tipo (
    id INT NOT NULL primary key auto_increment,
    nome VARCHAR(200) NOT NULL
);


create table permissao (
	id char(1) not null primary key,
	nome varchar (20) not null
);

 create table perfil_permissao (
	perfil char(5) not null,
	permissao varchar (4) not null
);

 create table perfil (
	perfil char(5) not null primary key,
	nome varchar (20) not null
);


CREATE TABLE menu (
  id int NOT NULL primary key auto_increment,
  label varchar(50) NOT NULL,
  submenu char(1) DEFAULT 'N',
  caminho varchar(200) NOT NULL,
  pai int,
  ordem int,
  dominio char(1) default 'N'
);

CREATE TABLE menu_perfil (
  menu int NOT NULL,
  perfil varchar(5) NOT NULL,
  PRIMARY KEY (menu, perfil)
);

CREATE TABLE dominio (
  id int NOT NULL primary key auto_increment,
  nome varchar(200) NOT NULL,
  alias varchar(50),
  descricao text,
  css varchar(50),
  diretorio varchar(50),
  mensagem text
);


CREATE TABLE dominio_usuario (
  usuario int NOT NULL auto_increment,
  dominio int NOT NULL,
  perfil char(5),
  PRIMARY KEY (usuario, dominio)
);


create table disciplina (
id int not null primary key auto_increment,
nome varchar(200) not null,
dominio int not null,
descricao text default null
)

create table competencia (
id int not null primary key auto_increment,
nome varchar(50) not null,
disciplina int not null
)

CREATE TABLE disciplina_objetivo (
  disciplina int NOT NULL,
  objetivo int NOT NULL,
  PRIMARY KEY (disciplina, objetivo)
);


CREATE TABLE slide (
  id int auto_increment NOT NULL primary key,
  enunciado text,
  enunciado_html text,
  objetivo int NOT NULL,
  disciplina int NOT NULL
  posicao integer,
  status char(1) DEFAULT 'I',
  correta char(1),
  comentario text,
  slide_tipo char(3) NOT NULL,
  parent int,
  peso float NOT NULL DEFAULT 1,
  content_html text,
  titulo varchar(300),
  arquivo blob,
  usuario int,
  incluidoem datetime,
  numero int,
  nivel char(1) DEFAULT 'F',
);


CREATE TABLE slide_tipo (
  id char(3) NOT NULL primary key,
  nome varchar(50)
);




#insert pessoa
insert into pessoa (nome, email) values
('Professor Azambuja', 'azambuja@professor.com'), 
('Professor Gensa','gensa@professor.com.br');

#insert usuario
insert into usuario (usuario, perfil, pessoa, password) values
('azambuja@professor.com', 'PRF', 3, 123), ('gensa@professor.com', 'PRF', 4, 123);

#criação de objetivos
insert into objetivo (nome, descricao, objetivo_tipo, parent, leaf, dominio) values
('4º Ano', 'Reforçar os conhecimentos adquiridos no quarto ano no colégio Gensa - Gravataí', 1, null, 'N', 1),
('1 Trimestre', 'Reforçar os conhecimentos adquiridos no 1º trimestre', 3, 1, 'S', 1 ),
('2 Trimestre', 'Reforçar os conhecimentos adquiridos no 2º trimestre', 3, 1, 'S', 1),
('3 Trimestre', 'Reforçar os conhecimentos adquiridos no 3º trimestre', 3, 1, 'S', 1);

#criação de objetivo tipo
insert into objetivo_tipo (nome) values
('Ano letivo'), 
('Semestre'),
('Trimestre'),
('Bimestre');

#INSERT perfil_permissao
insert into perfil_permissao (perfil, permissao) values
('ADM', 'CRUD'), ('PRF', 'CRUD');

#Insert permissão
insert into permissao (id, nome) values
('R', 'Ler'), ('I', 'inserir'), ('U', 'alterar'), ('D', 'remover');


#Insert usuario_perfil
insert into perfil (perfil, nome) values ('ADM', 'Administrador'), ('PRF', 'Professor');

#insert menu
insert into menu (label, submenu, caminho, pai, ordem, home) values 
('Objetivos', 'N', 'listObjetivos.php', null, 1, 'S'),
('Disciplinas', 'N', 'listDisciplinas.php', null, 2, 'S'),
('Usuários', 'N', 'usuarios.php', null, 1, 'N');

#insert menu_perfil
insert into menu_perfil (menu, perfil) values
(1, 'ADM'), (2, 'ADM'), (3, 'ADM');

#insert Dominio
insert into dominio (nome, alias, descricao, diretorio, mensagem) values 
('Escola Cenecista Nossa Senhora dos Anjos', 'Gensa', 'Atividades de fixação do aprendizado dos conteúdos adquiridos na escola', 'gensa', 'Bim vindo. Escola Cenecista Nossa Senhora dos Anjos'),
('Azambuja Cursos Preparatórios', 'Azambuja', 'Atividades de fixação do aprendizado dos conteúdos adquiridos no curso', 'azambuja', 'Bem vindo ao Azambuja');


#insert dominio_usuario
insert into dominio_usuario (usuario, dominio, perfil) values
(3, 3, 'PRF'), (4, 2, 'PRF');


#insert tipo de slides
insert into slide_tipo (id, nome) values
('QH', 'Questão HTML'), ('QT', 'Questão texto'), ('SL', 'Slide');

