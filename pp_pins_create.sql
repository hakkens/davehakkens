CREATE TABLE IF NOT EXISTS pp_pins (
  ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  user_ID bigint(20) unsigned NOT NULL,
  name VARCHAR(255) NOT NULL,
  lat DOUBLE,
  lng DOUBLE,
  description TEXT,
  address TEXT,
  website VARCHAR(255),
  contact VARCHAR(255) NOT NULL,
  hashtags JSON,
  filters JSON,
  imgs JSON,
  status VARCHAR(32) NOT NULL,
  show_on_map BOOLEAN DEFAULT false,
  PRIMARY KEY(`ID`),
  KEY `user_ID` (`user_ID`)
);
