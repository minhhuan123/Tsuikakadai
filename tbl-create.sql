CREATE DATABASE IF NOT EXISTS lesson2 DEFAULT CHARACTER SET utf8mb4;
use lesson2;

DROP TABLE IF EXISTS members;
CREATE TABLE members (
    id INT(10) AUTO_INCREMENT,
    email VARCHAR(128) NOT NULL UNIQUE,
    user VARCHAR(128) NOT NULL,
    password VARCHAR(100),
    name VARCHAR(128),
    address VARCHAR(128),
    gender INT(1),
    picture VARCHAR(256),
    locked INT(1) DEFAULT 0,
    activated INT(1) DEFAULT 0,
    banned INT(1) DEFAULT 0,
    lastlogin DATETIME,
    createdate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updatedate DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(id)
) DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS posts;
CREATE TABLE posts (
  id int(11) NOT NULL AUTO_INCREMENT,
  message text NOT NULL,
  member_id int(11) NOT NULL,
  reply_post_id int(11) NOT NULL,
  created datetime NOT NULL,
  modified timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

DROP TABLE IF EXISTS operations;
CREATE TABLE operations (
    id INT(10) AUTO_INCREMENT,
    email VARCHAR(128) NOT NULL UNIQUE,
    password VARCHAR(100),
    name VARCHAR(128),
    address VARCHAR(128),
    gender INT(1),
    lastlogin DATETIME,
    createdate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updatedate DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(id)
) DEFAULT CHARSET=utf8mb4;

