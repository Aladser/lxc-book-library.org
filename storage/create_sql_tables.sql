drop table if exists db_users;
drop table if exists vk_users;
drop table if exists google_users;
drop table if exists books;
drop table if exists auth_service_users;
drop table if exists auth_services;


CREATE table auth_services (
	id int auto_increment primary key,
	name varchar(255) not null unique
);

-- пользователи ВК--
 CREATE TABLE auth_service_users (
  id int auto_increment primary key,
  login varchar(255) not null,
  token varchar(255),
  auth_service int not null,
  foreign key (auth_service) references auth_services(id) on delete cascade
);

-- пользователи ВК--
 CREATE TABLE vk_users (
  id int auto_increment primary key,
  login varchar(255),
  token varchar(255)
);

-- пользователи Google--
 CREATE TABLE google_users (
  id int auto_increment primary key,
  login varchar(255),
  token varchar(255)
);

-- пользователи БД--
CREATE TABLE db_users (
  id int auto_increment primary key,
  login varchar(50) unique not null, 
  password varchar(255) not null
);

-- книги -- 
create table books(
	id int auto_increment primary key,
	author int not null,
	name varchar(255) not null,
	genre int,
	year int check(year > 0),
	description varchar(100),
	mark int check(mark > 0 and mark < 6),
	index uniq_author_name (author, name)
);