create database posts_web_app;
use posts_web_app;

create table posts (
   id int auto_increment not null primary key,
   title varchar(100) not null,
   body varchar(512) not null,
   created_at datetime not null,
   updated_at datetime
);

# insert into posts (title, body, created_at) values("test1", "body1", NOW());
# update posts set updated_at = NOW() where id=1;
# delete from posts where id = 1;
