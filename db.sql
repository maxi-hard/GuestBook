CREATE DATABASE IF NOT EXISTS tests__maxi_hard;

use tests__maxi_hard;

CREATE TABLE reviews
(
  ID       INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title    VARCHAR(256) NOT NULL,
  email    VARCHAR(256) NOT NULL,
  text     varchar(3000),
  accepted bit
);
