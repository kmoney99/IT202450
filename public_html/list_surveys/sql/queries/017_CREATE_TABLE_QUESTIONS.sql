CREATE TABLE IF NOT EXISTS  `Question`
(
    `id`         int auto_increment not null,
    `question` varchar(240),
    `user_id` int,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES Users(`id`)
    
)