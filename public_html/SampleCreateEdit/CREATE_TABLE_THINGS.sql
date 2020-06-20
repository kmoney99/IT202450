CREATE TABLE Things (
    id int auto_increment,
	question_id int,
	answer_id int,
    created datetime default current_timestamp,
    modified datetime default current_timestamp onupdate current_timestamp,
    primary key (id)
	
)