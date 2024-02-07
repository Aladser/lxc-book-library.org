drop table if exists db_users;
drop table if exists auth_service_users;
drop table if exists auth_services;
drop table if exists books;
drop table if exists genres;
drop table if exists authors;


-- виды сервисов авторизации -- 
CREATE table auth_services (
	id int auto_increment primary key,
	name varchar(255) not null unique
);
insert into auth_services(name) values('vk'), ('google');
-- пользователи внешних сервисов--
 CREATE TABLE auth_service_users (
  id int auto_increment primary key,
  login varchar(255) not null,
  token varchar(255),
  auth_service_id int not null references auth_services(id) on delete cascade,
  unique(login, auth_service_id)
);
-- пользователи БД--
CREATE TABLE db_users (
  id int auto_increment primary key,
  login varchar(50) unique not null,
  is_admin bool default false,
  nickname varchar(255) unique,  
  password varchar(255) not null
);
insert into db_users(login, nickname, is_admin, password) values(
	'aladser@mail.ru', 
	'Admin', 
	1, 
	'$2y$10$jP0Iw1RcMurRIcnFDhV8buhd/hCbEbs68WTBplERdiUNn3SO.u.zW'
);
insert into db_users(login, password) values(
	'aladser@yandex.ru', 
	'$2y$10$jP0Iw1RcMurRIcnFDhV8buhd/hCbEbs68WTBplERdiUNn3SO.u.zW'
);


-- авторы --
create table authors (
	id int auto_increment primary key,
	name varchar(255) not null,
	surname varchar(255) not null,
	patronym varchar(255),
	unique(name, surname)
);
-- жанры --
create table genres (
	id int auto_increment primary key,
	name varchar(255) unique not null
);
insert into genres(name) values ('классика'),('фантастика'),('фэнтези'),('ужасы');
-- книги -- 
create table books(
	id int auto_increment primary key,
	author_id int not null references authors(id) on delete cascade,
	name varchar(255) not null,
	picture varchar(255),
	genre_id int references genres(id) on delete cascade,
	year int check(year > 0),
	description varchar(300),
	index uniq_author_name (author_id, name)
);