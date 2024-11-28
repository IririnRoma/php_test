CREATE TABLE IF NOT EXISTS `comments` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `author` VARCHAR(50) NOT NULL,
    `content` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;