DROP DATABASE IF EXISTS `lock_app`;

CREATE DATABASE `lock_app` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

use `lock_app`;

DROP TABLE IF EXISTS client;

CREATE TABLE `client` (
    `client_id` int NOT NULL auto_increment primary KEY,
    `name` varchar(50) NOT NULL,
    `create_at` datetime DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS locker;

CREATE TABLE `locker` (
    `locker_id` int NOT NULL auto_increment primary KEY,
    `client_id` int NOT NULL,
    `macAdd` varchar(250) NOT NULL,
    `state` tinyint NOT NULL DEFAULT 1,
    `create_at` datetime DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`client_id`) REFERENCES `client` (`client_id`)
);

DROP TABLE IF EXISTS user;

CREATE TABLE `user` (
    `user_id` int NOT NULL auto_increment primary KEY,
    `client_id` int NOT NULL,
    `name` varchar(250) NOT NULL,
    `state` tinyint NOT NULL DEFAULT 1,
    `create_at` datetime DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`client_id`) REFERENCES `client` (`client_id`)
);

DROP TABLE IF EXISTS controller;

CREATE TABLE `controller` (
    `controller_id` int NOT NULL auto_increment primary KEY,
    `locker_id` int NOT NULL,
    `address485` varchar(4) NOT NULL,
    `create_at` datetime DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`locker_id`) REFERENCES `locker` (`locker_id`)
);

DROP TABLE IF EXISTS door_size;

CREATE TABLE `door_size` (
    `door_size_id` int NOT NULL auto_increment primary KEY,
    `name` varchar(250) NOT NULL,
    `create_at` datetime DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS door;

CREATE TABLE `door` (
    `door_id` int NOT NULL auto_increment primary KEY,
    `door_size_id` int NOT NULL,
    `controller_id` int NOT NULL,
    `number` int NOT NULL,
    `channel` varchar(250) NOT NULL,
    `state` tinyint(1) NOT NULL DEFAULT 1,
    `create_at` datetime DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`controller_id`) REFERENCES `controller` (`controller_id`),
    FOREIGN KEY (`door_size_id`) REFERENCES `door_size` (`door_size_id`)
);

DROP TABLE IF EXISTS movement;

CREATE TABLE `movement` (
    `movement_id` BIGINT NOT NULL auto_increment primary KEY,
    `door_id` int NOT NULL,
    `user_id` int NOT NULL,
    `client_id` int NOT NULL,
    `code` TEXT NOT NULL,
    `delivered` tinyint(1) NOT NULL DEFAULT 0,
    `id_ref` TEXT NOT NULL,
    `send_delivery` TINYINT(1) NOT NULL DEFAULT 0,
    `send_completed` TINYINT(1) NOT NULL DEFAULT 0,
    `create_at` datetime DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`client_id`) REFERENCES `client` (`client_id`),
    FOREIGN KEY (`door_id`) REFERENCES `door` (`door_id`),
    FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
);

###

INSERT INTO `client` (`client_id`, `name`) VALUES
(1, 'Experience');

INSERT INTO
    `locker` (`client_id`, `macAdd`)
VALUES (1, 'e34233ee23234'),
    (1, 'e34d43d34dee');

INSERT INTO
    `user` (`client_id`, `name`)
VALUES (1, '1'),
    (1, '2'),
    (1, '3'),
    (1, '4'),
    (1, '5'),
    (1, '6'),
    (1, '7'),
    (1, '8'),
    (1, '9'),
    (1, '10'),
    (1, '11'),
    (1, '12'),
    (1, '13'),
    (1, '14'),
    (1, '15'),
    (1, '16'),
    (1, '17'),
    (1, '18');

INSERT INTO
    `controller` (`locker_id`, `address485`)
VALUES (1, 'x434'),
    (1, 'x464'),
    (1, 'x334');

INSERT INTO
    `door_size` (`name`)
VALUES ('Pequeño'),
    ('Mediano'),
    ('Grande');

INSERT INTO
    `door` (
        `door_size_id`,
        `controller_id`,
        `number`,
        `channel`
    )
VALUES (1, 1, 1, 'channel'),
    (1, 1, 2, 'channel2'),
    (1, 1, 3, 'channel3'),
    (1, 1, 4, 'channe4'),
    (2, 2, 5, 'channel5'),
    (2, 2, 6, 'channel6'),
    (2, 2, 7, 'channel7'),
    (3, 3, 8, 'channel8'),
    (3, 3, 9, 'channel9');

INSERT INTO `movement` (`door_id`, `code`) VALUES (1, '4464845233');

#####

select * from locker;
##armario
select * from client;
##edificio
select * from controller;
##placa
select * from door;
#casilleros
select * from user;
##departamento
select * from movement;

select *
from door
    inner join door_size on door.door_size_id = door_size.door_size_id;