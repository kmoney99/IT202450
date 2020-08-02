CREATE TABLE IF NOT EXISTS  `Answers`
(
    `id`     int auto_increment not null,
    `answer` varchar(240),
    `question_id` int,
    PRIMARY KEY (`id`)
)