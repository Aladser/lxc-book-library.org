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
	unique(name, surname)
);
insert into authors(name, surname) values
	('Стивен','Кинг'),
	('Александр','Пушкин'),
	('Дмитрий','Глуховский'),
	('Терри','Пратчетт'),
	('Филип','Дик'),
	('Дэниэл','Киз');

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
	description text,
	index uniq_author_name (author_id, name)
);

insert into books(author_id, name, genre_id, year, description) values
(1, 'Мизери', 4, 1985, 'Может ли спасение от верной гибели обернуться таким кошмаром, что даже смерть покажется милосердным даром судьбы?
Может. Ибо это произошло с Полом Шелдоном, автором бесконечного сериала книг о злоключениях Мизери. Раненый писатель оказался в руках Энни Уилкс – женщины, потерявшей рассудок на почве его романов. Уединенный домик одержимой бесами фурии превратился в камеру пыток, а существование Пола – в ад, полный боли и ужаса.'),
(4, "Творцы заклинаний", 3, 1987, "По бескрайнему пространству плывет Великий А`Туин - всемирная черепаха. На спине ее стоят четыре слона, которые несут самый необычный груз во Вселенной - Плоский мир. Там живут тролли и драконы, варвары и друиды. Там создают волшебство самые могущественные маги на свете - Творцы заклинаний."),
(6, "Цветы для Элджернона", 2, 1959, "Научно-фантастический роман американского писателя Даниэла Киза, претерпевший более сорока изданий на родине автора и широко известный во всем мире. Поизведение посвящено теме ответственности ученого за эксперименты над человеком."),
(4, "Мор - ученик Смерти", 3, 1987, "Смерть ловит рыбу. Веселится на вечеринке. Напивается в трактире. А все обязанности Мрачного Жреца сваливаются на хрупкие плечи его ученика. Но делать нечего: берем косу, прыгаем на белую лошадь Бинки - и вперед!");
insert into books(author_id, name, genre_id, year, description, picture) values
(3, "Будущее", 2, 2013, "НА ЧТО ТЫ ГОТОВ РАДИ ВЕЧНОЙ ЖИЗНИ? Уже при нашей жизни будут сделаны открытия, которые позволят людям оставаться вечно молодыми. Смерти больше нет. Наши дети не умрут никогда. Добро пожаловать в будущее. В мир, населенный вечно юными, совершенно здоровыми, счастливыми людьми. Но будут ли они такими же, как мы? Нужны ли дети, если за них придется пожертвовать бессмертием? Нужна ли семья тем, кто не может завести детей? Нужна ли душа людям, тело которых не стареет?.", "/storage/data/images/buduschee.jpeg"),
(1, "Зеленая миля", 4, 1996, "Стивен Кинг приглашает читателей в жуткий мир тюремного блока смертников, откуда уходят, чтобы не вернуться, приоткрывает дверь последнего пристанища тех, кто преступил не только человеческий, но и Божий закон. По эту сторону электрического стула нет более смертоносного местечка! Ничто из того, что вы читали раньше, не сравнится с самым дерзким из ужасных опытов Стивена Кинга — с историей, что начинается на Дороге Смерти и уходит в глубины самых чудовищных тайн человеческой души..", "/storage/data/images/zelenaya_milya.jpeg");
