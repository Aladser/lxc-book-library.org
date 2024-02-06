drop table if exists db_users;
drop table if exists auth_service_users;
drop table if exists auth_services;
drop table if exists books;

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
  auth_service_id int not null,
  unique(login, auth_service_id),
  foreign key (auth_service_id) references auth_services(id) on delete cascade
);

-- пользователи БД--
CREATE TABLE db_users (
  id int auto_increment primary key,
  login varchar(50) unique not null,
  is_admin bool default false,
  nickname varchar(255) unique,  
  password varchar(255) not null
);
-- пароль 111 --
insert into db_users(login, nickname, is_admin, password) values('aladser@mail.ru', 'Admin', 1, '$2y$10$jP0Iw1RcMurRIcnFDhV8buhd/hCbEbs68WTBplERdiUNn3SO.u.zW');

-- книги -- 
create table books(
	id int auto_increment primary key,
	author int not null,
	name varchar(255) not null,
	picture varchar(255),
	genre int,
	year int check(year > 0),
	description varchar(100),
	mark int check(mark > 0 and mark < 6),
	index uniq_author_name (author, name)
);
