drop table if exists db_users;
drop table if exists vk_users;
drop table if exists google_users;


-- пользователи БД
CREATE TABLE db_users (
  id int auto_increment primary key,
  login varchar(50) unique not null, 
  password varchar(255) not null
);

-- пользователи ВК
 CREATE TABLE vk_users (
  id int auto_increment primary key,
  login varchar(15),
  token varchar(255)
);

-- пользователи Google
 CREATE TABLE google_users (
  id int auto_increment primary key,
  login varchar(15),
  token varchar(255)
);