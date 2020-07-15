CREATE TABLE Questions (
    id int auto_increment,
    Question varchar(20) not null unique,
    Answer varchar(20) not null unique,
    created datetime default current_timestamp,
    modified datetime default current_timestamp on update CURRENT_TIMESTAMP,
    accessed_date DATETIME default current_timestamp,
    primary key(id)
)