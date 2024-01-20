drop table if exists db_users;
drop table if exists vk_users;
drop table if exists users;

# --- пользователи
create table users
(
    id int auto_increment primary key,
    db_user_id int unique,
    vk_user_id int unique
);

-- пользователи БД
CREATE TABLE db_users (
  id int auto_increment primary key,
  login varchar(50) unique not null, 
  password varchar(255) not null
);

-- пользователи ВК
 CREATE TABLE vk_users (
  id varchar(15) PRIMARY KEY, 
  name varchar(255) NOT NULL unique
);