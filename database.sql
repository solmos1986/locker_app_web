DROP DATABASE IF EXISTS `lock_app`;

CREATE DATABASE `lock_app` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

use `lock_app`;

DROP TABLE IF EXISTS client;

CREATE TABLE `client` (
    `client_id` int NOT NULL auto_increment primary KEY,
    `name` varchar(50) NOT NULL,
    `create_at` datetime DEFAULT CURRENT_TIMESTAMP,
    `update_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS locker;

CREATE TABLE `locker` (
    `locker_id` int NOT NULL auto_increment primary KEY,
    `client_id` int NOT NULL,
    `macAdd` varchar(250) NOT NULL,
    `state` tinyint NOT NULL DEFAULT 1,
    `create_at` datetime DEFAULT CURRENT_TIMESTAMP,
    `update_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`client_id`) REFERENCES `client` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS user;

CREATE TABLE `user` (
    `user_id` int NOT NULL auto_increment primary KEY,
    `client_id` int NOT NULL,
    `name` varchar(250) NOT NULL,
    `state` tinyint NOT NULL DEFAULT 1,
    `create_at` datetime DEFAULT CURRENT_TIMESTAMP,
    `update_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`client_id`) REFERENCES `client` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS controller;

CREATE TABLE `controller` (
    `controller_id` int NOT NULL auto_increment primary KEY,
    `locker_id` int NOT NULL,
    `address485` varchar(4) NOT NULL,
    `create_at` datetime DEFAULT CURRENT_TIMESTAMP,
    `update_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`locker_id`) REFERENCES `locker` (`locker_id`) ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS door_size;

CREATE TABLE `door_size` (
    `door_size_id` int NOT NULL auto_increment primary KEY,
    `name` varchar(250) NOT NULL,
    `create_at` datetime DEFAULT CURRENT_TIMESTAMP,
    `update_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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
    `update_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`controller_id`) REFERENCES `controller` (`controller_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`door_size_id`) REFERENCES `door_size` (`door_size_id`) ON DELETE CASCADE ON UPDATE CASCADE
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
    `update_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`client_id`) REFERENCES `client` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`door_id`) REFERENCES `door` (`door_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
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
VALUES (1, '101'),
    (1, '102'),
    (1, '103'),
    (1, '104'),
    (1, '105'),
    (1, '106'),
    (1, '201'),
    (1, '202'),
    (1, '203'),
    (1, '204'),
    (1, '205'),
    (1, '206'),
    (1, '301'),
    (1, '302'),
    (1, '303'),
    (1, '304'),
    (1, '305'),
    (1, '306'),
    (1, '401'),
    (1, '402'),
    (1, '403'),
    (1, '404'),
    (1, '405'),
    (1, '406'),
    (1, '501'),
    (1, '502'),
    (1, '503'),
    (1, '504'),
    (1, '505'),
    (1, '506'),
    (1, '601'),
    (1, '602'),
    (1, '603'),
    (1, '604'),
    (1, '605'),
    (1, '606'),
    (1, '701'),
    (1, '702');
###test
INSERT INTO
    `user` (`client_id`, `name`)
VALUES (1, '905');
INSERT INTO
    `user` (`client_id`, `name`)
VALUES (1, '906');
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

update user set name='101' where user.user_id= 1;
update user set name='102' where user.user_id= 2;
update user set name='103' where user.user_id= 3;
update user set name='104' where user.user_id= 4;
update user set name='105' where user.user_id= 5;
update user set name='106' where user.user_id= 6;

update user set name='201' where user.user_id= 7;
update user set name='202' where user.user_id= 8;
update user set name='203' where user.user_id= 9;
update user set name='204' where user.user_id= 10;
update user set name='205' where user.user_id= 11;
update user set name='206' where user.user_id= 12;

update user set name='301' where user.user_id= 13;
update user set name='302' where user.user_id= 14;
update user set name='303' where user.user_id= 15;
update user set name='304' where user.user_id= 16;
update user set name='305' where user.user_id= 17;
update user set name='306' where user.user_id= 18;

update user set name='401' where user.user_id= 1;
update user set name='402' where user.user_id= 1;
update user set name='403' where user.user_id= 1;
update user set name='404' where user.user_id= 1;
update user set name='405' where user.user_id= 1;
update user set name='406' where user.user_id= 1;

update user set name='501' where user.user_id= 1;
update user set name='502' where user.user_id= 1;
update user set name='503' where user.user_id= 1;
update user set name='504' where user.user_id= 1;
update user set name='505' where user.user_id= 1;
update user set name='506' where user.user_id= 1;

update user set name='601' where user.user_id= 1;
update user set name='602' where user.user_id= 1;
update user set name='603' where user.user_id= 1;
update user set name='604' where user.user_id= 1;
update user set name='605' where user.user_id= 1;
update user set name='606' where user.user_id= 1;

update user set name='701' where user.user_id= 1;
update user set name='702' where user.user_id= 1;