CREATE TABLE Survey(
    id int auto_increment,
    title varchar(30) not null unique,
    description TEXT,
    visibility int, 
    primary key (id)
)