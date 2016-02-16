CREATE SCHEMA `calendars` ;

CREATE TABLE `calendars`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(60) NOT NULL,
  `type` INT NOT NULL,
  `username` VARCHAR(60) NOT NULL,
  `password_hash` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`id`));

CREATE TABLE `calendars`.`calendars` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `type` ENUM('system', 'user') NOT NULL,
  PRIMARY KEY (`id`));

CREATE TABLE `calendars`.`events` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `description` VARCHAR(60) NOT NULL,
  `type` ENUM('system', 'user') NOT NULL,
  `from` INT(13) NOT NULL,
  `to` INT(13) NOT NULL,
  `location` VARCHAR(45) NULL,
  `comment` VARCHAR(45) NULL,
  PRIMARY KEY (`id`));

CREATE TABLE `calendars`.`users_calendars_events` (
  `event_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `calendar_id` INT NOT NULL,
   `added` INT(13) NOT NULL,
  PRIMARY KEY (`event_id`, `user_id`, `calendar_id`),
CONSTRAINT fk_userscalendars_users FOREIGN KEY (user_id)
REFERENCES users(id),
CONSTRAINT fk_userscalendars_calendars FOREIGN KEY (calendar_id)
REFERENCES calendars(id),
CONSTRAINT fk_users_calendar_events_calendars FOREIGN KEY (event_id)
REFERENCES events(id)
);
