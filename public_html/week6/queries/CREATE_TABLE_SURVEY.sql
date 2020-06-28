CREATE TABLE IF NOT EXISTS Survey (
    id int auto_increment,
	question_id int auto_increment, 
	title varchar(30) not null unique,
	description TEXT,
    created datetime default current_timestamp,
    modified datetime default current_timestamp on update current_timestamp,
    primary key (id)
)