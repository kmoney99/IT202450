CREATE TABLE Survey (
    id int auto_increment,
    title varchar(30) not null unique,
    description TEXT,
    status TEXT,
    userId int,
	created    timestamp default current_timestamp,
    modified   timestamp default current_timestamp on update current_timestamp,
    PRIMARY KEY (`id`)
)