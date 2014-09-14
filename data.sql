CREATE TABLE IF NOT EXISTS users (
  id   INT(11)      NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  password VARCHAR(32) NOT NULL,
  role TINYINT DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE (name)
);

INSERT INTO users (name, password, role) VALUES ('admin', 'd0970714757783e6cf17b26fb8e2298f', 3);
INSERT INTO users (name, password, role) VALUES ('vlad', '5b1b68a9abf4d2cd155c81a9225fd158', 1);

CREATE TABLE IF NOT EXISTS groups (
  id int(11) NOT NULL auto_increment,
  name varchar(100) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE (name)
);

INSERT INTO groups (name) VALUES ('Group1');
INSERT INTO groups (name) VALUES ('Group2');

CREATE TABLE IF NOT EXISTS users_to_groups (
  user_id int(11) NOT NULL,
  group_id int(11) NOT NULL
);

INSERT INTO users_to_groups (user_id, group_id) VALUES  (1, 1);