#To set up the databases

sudo apt-get install php
sudo apt-get install php7.0-mysql
sudo apt-get install mysql-server
sudo apt-get install postgresql-9.5
sudo apt-get install php7.0-pgsql

mysql

create database mysql_database;
create user 'mysql_user'@'localhost' identified by 'mysql_password';
grant all privileges on mysql_database.* to 'mysql_user'@'localhost';

create table cats(id int not null auto_increment primary key, name text, color text);
insert into cats(name, color) values ('fluffy', 'white');

psql

create database postgres_database;
create user postgres_user with password 'postgres_password';
grant all privileges on postgres_database to postgres_user;
\c postgres_database;
create table people(id serial primary key, name text, food text);
insert into people(name, food) values ('donald', 'pizza');
grant all privileges on all tables in schema public to postgres_user;
grant all privileges on all sequences in schema public to postgres_user;

